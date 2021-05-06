<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\Type\PlayType;
use App\Form\Type\RollType;
use Rois\Yatzy\YatzyGame;
use App\Entity\Player;

class YatzyController extends AbstractController
{
    /**
     *
     * @Route("/yatzy/play", name="yatzy_play", methods={"GET", "POST"})
     */
    public function play(Request $request): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayType::class, $player);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $playerData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $currentPlayer = $entityManager->getRepository(Player::class)->findBy(array('name' => $playerData->getName()));
            if (!$currentPlayer) {
                $player = new Player();
                $player->setName($playerData->getName());
                $player->setScore(0);
                $entityManager->persist($player);
                $entityManager->flush();
            }

            $yatzyObj = new YatzyGame();
            $yatzyObj->initGame();
            $yatzyObj->getCurrentRound()->roll();

            $this->get("session")->set("callable", serialize($yatzyObj));
            $this->get("session")->set("name", $playerData->getName());

            return $this->redirectToRoute('yatzy_controls');
        }
        return $this->render('yatzy/yatzy.html.twig', [
            'title' => 'Yatzy',
            'message' => 'Traditional Yatzy game.',
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/yatzy/highscore", name="yatzy_highscore", methods={"GET", "POST"})
     */
    public function highscore(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Player::class);
        $highscores = $repository->findAll();
        return $this->render('yatzy/highscore.html.twig', [
            'title' => 'Yatzy Highscores',
            'highscores' => $highscores
        ]);
    }

    /**
     *
     * @Route("/yatzy/controls", name="yatzy_controls", methods={"GET", "POST"})
     */
    public function controls(Request $request): Response
    {
        $gameObj = unserialize($this->get("session")->get("callable"));

        $addRemove = [
            "add-0" => 0,
            "add-1" => 1,
            "add-2" => 2,
            "add-3" => 3,
            "add-4" => 4,
            "rem-0" => 0,
            "rem-1" => 1,
            "rem-2" => 2,
            "rem-3" => 3,
            "rem-4" => 4
        ];

        $roll = $request->get("roll");
        $saveResult = $request->get("save_result");
        $newGame = $request->get("new_game");

        if ($roll) {
            $gameObj->getCurrentRound()->roll();
        } elseif ($saveResult) {
            $gameObj->saveRound(intval($_POST["save_position"]));
            $gameObj->newRound();
            $gameObj->getCurrentRound()->roll();
        } elseif ($newGame) {
            return $this->redirectToRoute('yatzy_play');
        }

        foreach ($addRemove as $index => $value) {
            $post = $request->get($index);
            if ($post === "") {
                $func = explode("-", $index);
                if ($func[0] === "add") {
                    $gameObj->getCurrentRound()->storeDices($value);
                    $this->get("session")->set("callable", serialize($gameObj));
                    return $this->redirectToRoute('yatzy_update');
                }

                $gameObj->getCurrentRound()->removeDices($value);
                $this->get("session")->set("callable", serialize($gameObj));
                return $this->redirectToRoute('yatzy_update');
            }
        }

        $this->checkEnd($gameObj);

        $this->get("session")->set("callable", serialize($gameObj));
        return $this->redirectToRoute('yatzy_update');
    }

    /**
     *
     * @Route("/yatzy/update", name="yatzy_update", methods={"GET", "POST"})
     */
    public function update(): Response
    {
        $gameObj = unserialize($this->get("session")->get("callable"));
        $playerName = $this->get("session")->get("player_name");
        $data = [
            "title" => "Yatzy",
            "message" => "Hello, " . $playerName . " go ahead and play!",
            "rounds" => $gameObj->getRounds(),
            "diceValues" => $gameObj->getCurrentRound()->getDiceHand()->values(),
            "savedValues" => $gameObj->getCurrentRound()->getStoredDices(),
            "end" => $gameObj->getCurrentRound()->checkEnd(),
            "saved" => $gameObj->getCurrentRound()->checkSaved(),
            "endGame" => $gameObj->checkEndGame(),
            "sum" => $gameObj->getTotalScore(),
            "bonus" => $gameObj->getTotalScore() >= 63 ? 50 : 0
        ];
        return $this->render('yatzy/yatzy_play.html.twig', $data);
    }

    /**
     * Checks if game is ended and if so prsists player score to database.
     */
    private function checkEnd($gameObj): void
    {
        if ($gameObj->checkEndGame()) {
            $name = $this->get("session")->get("name");
            $entityManager = $this->getDoctrine()->getManager();
            $player = $entityManager->getRepository(Player::class)->findOneBy(array('name' => $name));
            $totalScore = $gameObj->getTotalScore();
            if ($gameObj->getTotalScore() > 63) {
                $totalScore += 50;
            }

            //Check if current score is a new highscore and persist if so.
            if ($totalScore > $player->getScore()) {
                $player->setScore($totalScore);
                $entityManager->persist($player);
                $entityManager->flush();
            }
        }
    }
}
