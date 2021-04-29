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
        $gameObj;
        if ($this->get('session')->has('callable')) {
            $gameObj = unserialize($this->get('session')->get("callable"));
        } else {
            $gameObj = new Game();
            $gameObj->playGame(2);
        }

        $rollForm = $this->createForm(RollType::class);
        $stopForm = $this->createForm(StopType::class);
        $resetForm = $this->createForm(ResetType::class);

        $roll = $request->request->get("roll");
        $reset = $request->request->get("reset");
        $stop = $request->request->get("stop");

        //Handle roll button
        $rollForm->handleRequest($request);
        if ($rollForm->isSubmitted() && $rollForm->isValid()) {
            if ($roll) {
                $gameObj->newRoll();
                $this->get('session')->set('callable', serialize($gameObj));
            }
            return $this->redirectToRoute('dice_play');
        }

        //Handle reset button
        $resetForm->handleRequest($request);
        if ($resetForm->isSubmitted() && $resetForm->isValid()) {
            if ($reset) {
                $gameObj = new Game();
                $gameObj->playGame(2);
                $this->get('session')->set('callable', serialize($gameObj));
            }
            return $this->redirectToRoute('dice_play');
        }

        //Handle stop button
        $stopForm->handleRequest($request);
        if ($stopForm->isSubmitted() && $stopForm->isValid()) {
            if ($stop) {
                $gameObj->stop();
                $this->get('session')->set('callable', serialize($gameObj));
            }
            return $this->redirectToRoute('dice_play');
        }

        return $this->render('dice_21/dice_play.html.twig', [
            'title' => 'Dice 21',
            'message' => 'Roll the dices to get as close to 21 as possible.',
            'result' => $gameObj->getResult(),
            'diceValues' => $gameObj->getValues(),
            'sum' => $gameObj->getSum(),
            'rollForm' => $rollForm->createView(),
            'resetForm' => $resetForm->createView(),
            'stopForm' => $stopForm->createView()
        ]);
    }
}