<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findByPublished();
        return $this->render('/wish/list.html.twig', ["wishes" => $wishes]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show($id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        return $this->render('/wish/show.html.twig', ["wish" => $wish]);
    }

    #[Route('/add', name: 'add')]
    public function add(WishRepository $wishRepository): Response
    {
//        $wish = new Wish();
//        $wish->setTitle("Faire repousser mes cheveux")
//            ->setAuthor("Arthur")
//            ->setDescription("Je n'ai pas envie de devenir chauve")
//            ->setDateCreated(new \DateTime('now'))
//            ->setIsPublished(true);
//
//        $wishRepository->save($wish, true);
        //TODO récupérer formulaire
        return $this->render('/wish/add.html.twig');
    }
}
