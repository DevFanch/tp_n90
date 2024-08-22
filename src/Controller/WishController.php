<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            // Check File
            if ($wishForm->get('image')->getData() !== null) {
                $image = $wishForm->get('image')->getData();
                $fileName = $fileUploader->upload($image);
                $wish->setImage($fileName);
            }

            $em->persist($wish);
            $em->flush();
            $this->addFlash('success', 'Idea successfully added !');

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'title' => 'Add your wishes!',
            'wishForm' => $wishForm
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Wish $wish, Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            // Check File
            $image = $wishForm->get('image')->getData();
            if ($wishForm->has('deleteImage') && $wishForm->get('deleteImage')->getData()) {
                $fileUploader->delete($wish->getImage());
                $wish->setImage(null);
            }

            // Save file
            if ($image !== null) {
                $fileName = $fileUploader->upload($image);
                $wish->setImage($fileName);
            }
            $em->flush();
            $this->addFlash('success', 'Idea successfully updated !');

            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'title' => 'Edit your wishes!',
            'wishForm' => $wishForm
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'])]
    public function delete(Wish $wish, EntityManagerInterface $em): Response
    {
        $em->remove($wish);
        $em->flush();
        $this->addFlash('success', 'Idea successfully deleted !');

        return $this->redirectToRoute('wish_list');
    }
}
