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

class YatzyController extends AbstractController
{
    /**
     *
     * @Route("/yatzy/play", name="yatzy_play", methods={"GET", "POST"})
     */
    public function play(Request $request): Response
    {
        $form = $this->createForm(PlayType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $yatzyObj = new YatzyGame();
            $yatzyObj->initGame();
            $yatzyObj->getCurrentRound()->roll();
            $this->get("session")->set("callable", serialize($yatzyObj));

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

        $gameObj = unserialize($this->get("session")->get("callable"));
        if (isset($_POST["roll"])) {
            $gameObj->getCurrentRound()->roll();
        } elseif (isset($_POST["save_result"])) {
            echo("Save");
            $gameObj->saveRound(intval($_POST["save_position"]));
            $gameObj->newRound();
            $gameObj->getCurrentRound()->roll();
        } elseif (isset($_POST["new_game"])) {
            $gameObj = new YatzyGame();
            $gameObj->initGame();
        }

        foreach ($addRemove as $index => $value) {
            if (isset($_POST[$index])) {
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

        $this->get("session")->set("callable", serialize($gameObj));
        return $this->redirectToRoute('yatzy_update');    
    }

    /**
     *
     * @Route("/yatzy/update", name="yatzy_update", methods={"GET", "POST"})
     */
    public function update(Request $request): Response
    {
        $gameObj = unserialize($this->get("session")->get("callable"));
        $data = [
            "title" => "Yatzy",
            "message" => "Hello, this is the dice page.",
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
}
