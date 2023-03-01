<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(CategoryRepository $categoryRepository): Response
    {
        $catAndWishes = $categoryRepository->findWishByCat();

        return $this->render('/wish/list.html.twig', [
            "catAndWishes" => $catAndWishes,

            ]);
    }
    //ou
    /*
      #[Route('/list', name: 'list')]
        public function list(WishRepository $wishRepository): Response
        {
        $wishes = $WishRepository->findPublishWishes();

        return $this->render('/wish/list.html.twig', [
            "wishes" => $wishes,

            ]);
    }

     */

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        if (!$wish){
            throw $this->createNotFoundException("Oops, wish not found !");
        }
        return $this->render('/wish/show.html.twig', ["wish" => $wish]);
    }

    #[Route('/add', name: 'add')]
    public function add(WishRepository $wishRepository, Request $request, Censurator $censurator): Response
    {
        $wish = new Wish();
        //valeur par défaut de l'auteur = pseudo de l'utilisateur
        $wish->setAuthor($this->getUser()->getUserIdentifier());
        $wishForm = $this->createForm(WishType::class, $wish);
        //$wishForm->add('author', TextType::class, ['attr' => ['value' => $this->getUser()->getUserIdentifier()]]);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

            $words = $this->getParameter('censored_words');
            foreach ($words as $word){
                $title = $wish->getTitle();
                $wish->setTitle($censurator->purify($word, $title));
            }

            foreach ($words as $word){
                $description = $wish->getDescription();
                $wish->setDescription($censurator->purify($word, $description));
            }

            $wishRepository->save($wish, true);
            $this->addFlash("success", "Idea successfully added!");
            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
        }

        return $this->render('/wish/add.html.twig', ["wishForm" => $wishForm->createView()]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(int $id, WishRepository $wishRepository, Request $request, Censurator $censurator): Response
    {
        $wish = $wishRepository->find($id);
        if (!$wish){
            throw $this->createNotFoundException("Oops, wish not found !");
        }

        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

            $words = $this->getParameter('censored_words');

            foreach ($words as $word) {
                $wish->setTitle($censurator->purify($word, $wish->getTitle()));
                $wish->setDescription($censurator->purify($word, $wish->getDescription()));
            }

            $wishRepository->save($wish, true);
            $this->addFlash("success", "Idea successfully updated!");
            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
        }
        return $this->render('/wish/update.html.twig', [
            "wish" => $wish,
            "wishForm" => $wishForm->createView()
        ]);
    }
}
