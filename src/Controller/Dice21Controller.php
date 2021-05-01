<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\Type\RollType;
use App\Form\Type\ResetType;
use App\Form\Type\StopType;
use Rois\Dice\Game;

class Dice21Controller extends AbstractController
{
    /**
     *
     * @Route("/dice/play", name="dice_play", methods={"GET", "POST"})
     */
    public function play(Request $request): Response
    {
        $gameObj = null;
        $gameObj = $this->configureGameObj();

        $rollForm = $this->createForm(RollType::class);
        $stopForm = $this->createForm(StopType::class);
        $resetForm = $this->createForm(ResetType::class);

        $this->handleRollForm($request, $rollForm, $gameObj);
        $this->handleStopForm($request, $stopForm, $gameObj);

        $reset = $request->request->get("reset");

        //Handle reset button
        $resetForm->handleRequest($request);
        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            if ($reset) {
                $this->get('session')->remove('callable');
            }
            return $this->redirectToRoute('dice_index');
        }
        $winner = $gameObj->getWinner();
        $this->handleWinner($winner);

        $playerScore = $this->get("session")->get("player");
        $computerScore = $this->get("session")->get("computer");

        return $this->render('dice_21/dice_play.html.twig', [
            'title' => 'Dice 21',
            'message' => 'Roll the dices to get as close to 21 as possible.',
            'result' => $gameObj->getResult(),
            'diceValues' => $gameObj->getValues(),
            'sum' => $gameObj->getSum(),
            'playerScore' => $playerScore,
            'computerScore' => $computerScore,
            'rollForm' => $rollForm->createView(),
            'resetForm' => $resetForm->createView(),
            'stopForm' => $stopForm->createView()
        ]);
    }

    /**
     *
     * @Route("/dice/reset_score", name="reset_score", methods={"GET", "POST"})
     */
    public function resetScore(): Response
    {
        $this->get('session')->remove('player');
        $this->get('session')->remove('computer');
        return $this->redirectToRoute('dice_play');
    }

    private function configureGameObj(): Game
    {
        $obj = null;
        if ($this->get('session')->has('callable')) {
            $obj = unserialize($this->get('session')->get("callable"));
            return $obj;
        }
        $obj = new Game();
        $obj->playGame($this->get('session')->get("dices"));
        return $obj;
    }

    private function handleWinner($winner): void
    {
        //Add to score for winner if available
        if ($winner) {
            $storedScore = intval($this->get("session")->get($winner));
            if ($this->get("session")->has($winner)) {
                $storedScore = intval($this->get("session")->get($winner));
                $this->get("session")->set($winner, $storedScore + 1);
                return;
            }
            $this->get("session")->set($winner, 1);
            return;
        }
    }

    private function handleRollForm(Request $request, $rollForm, $gameObj)
    {
        $roll = $request->request->get("roll");

        //Handle roll button
        $rollForm->handleRequest($request);
        if ($rollForm->isSubmitted() && $rollForm->isValid()) {
            if ($roll) {
                $gameObj->newRoll();
                $this->get('session')->set('callable', serialize($gameObj));
            }
            return $this->redirectToRoute('dice_play');
        }
    }

    private function handleStopForm(Request $request, $stopForm, $gameObj)
    {
        $stop = $request->request->get("stop");

        //Handle stop button
        $stopForm->handleRequest($request);
        if ($stopForm->isSubmitted() && $stopForm->isValid()) {
            if ($stop) {
                $gameObj->stop();
                $this->get('session')->set('callable', serialize($gameObj));
            }
            return $this->redirectToRoute('dice_play');
        }
    }
}
