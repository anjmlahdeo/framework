<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Type\GameType;
use App\Form\Type\PlayType;
use Rois\Dice\Game;
use Rois\Yatzy\YatzyGame;
use App\Entity\Book;

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
    public function dice(Request $request): Response
    {
        $form = $this->createForm(GameType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $gameObj = new Game();
            $gameObj->playGame($data['dices']);

            $this->get('session')->set('callable', serialize($gameObj));
            $this->get('session')->set('dices', $data['dices']);

            return $this->redirectToRoute('dice_play');
        }

        // $this->session->set("callable", serialize(new Game()));
        return $this->render('dice_21/dice_index.html.twig', [
            'title' => 'Dice 21',
            "message" => "Select the number of dices to use",
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/yatzy", name="yatzy_index")
     */
    public function yatzy(Request $request): Response
    {
        $form = $this->createForm(PlayType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $yatzyObj = new YatzyGame();
            $this->get("session")->set("callable", serialize($yatzyObj));

            return $this->redirectToRoute('yatzy_play');
        }

        return $this->render('yatzy/yatzy.html.twig', [
            'title' => 'Yatzy',
            'message' => 'Traditional Yatzy game.',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/books", name="books_index")
     */
    public function books(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $books = $repository->findAll();
        return $this->render('books/books.html.twig', [
            'title' => 'Books',
            'books' => $books
        ]);
    }
}
