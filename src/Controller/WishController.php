<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(): Response
    {
        return $this->render('wish/list.html.twig', [
            'title' => 'Wish List ',
        ]);
    }
    // #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    #[Route('/{id<\d+>}', name: 'detail')]
    public function detail(int $id = 1): Response
    {
        $wish = [
            'id' => $id,
            'title' => 'Repartir en vacances',
            'description' => 'Parce que c\'est bien les vacances'
        ];

        return $this->render('wish/detail.html.twig', [
            'title' => 'Wish detail ',
            'wish' =>  $wish
        ]);
    }
}
