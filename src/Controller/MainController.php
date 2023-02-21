<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        //TODO fonction accueil
        return $this->render('main/home.html.twig');
    }

    #[Route('/about', name: 'main_about')]
    public function about(): Response
    {
        //TODO
        return $this->render('main/about.html.twig');
    }
}
