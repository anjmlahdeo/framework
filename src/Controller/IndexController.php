<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     *
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'The Dice Page',
        ]);
    }

    /**
     * @Route("/dice", name="dice_index")
     */
    public function dice(): Response
    {
        return $this->render('dice.html.twig', [
            'title' => 'Dice 21',
        ]);
    }

    /**
     * @Route("/yatzy", name="yatzy_index")
     */
    public function yatzy(): Response
    {
        return $this->render('yatzy.html.twig', [
            'title' => 'Yatzy',
        ]);
    }
}