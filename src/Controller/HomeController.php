<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Ceci est la page d\'Accueil',
        ]);
    }

    #[Route('/about-us', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'title' => 'About Us',
        ]);
    }
}
