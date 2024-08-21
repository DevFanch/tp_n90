<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(WishRepository $wishR): Response
    {
        return $this->render('wish/list.html.twig', [
            'title' => 'Wish List ',
            // 'wishes' => $wishR->findLastPublished()
            'wishes' => $wishR->findBy(['isPublished' => true], ['dateCreated' => 'DESC'])
        ]);
    }
    // #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    #[Route('/{id<\d+>}', name: 'detail')]
    // public function detail(int $id, WishRepository $wishR): Response
    public function detail(Wish $wish): Response
    {
        // Long version
        // $wish = $wishR->find($id);
        // if (!$wish) {
        //     throw $this->createNotFoundException('Wish not found');
        // }

        // Short version with EntityResolverValue
        return $this->render('wish/detail.html.twig', [
            'title' => 'Wish detail',
            'wish' => $wish
        ]);
    }
}
