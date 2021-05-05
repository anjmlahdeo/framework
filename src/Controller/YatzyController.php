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
use Rois\Yatzy\YatzyGame;

class YatzyController extends AbstractController
{
    /**
     *
     * @Route("/yatzy/play", name="yatzy_play", methods={"GET", "POST"})
     */
    public function play(Request $request): Response
    {
        return $this->render('yatzy/yatzy_play.html.twig', [
            'title' => 'Yatzy',
        ]);
    }
}
