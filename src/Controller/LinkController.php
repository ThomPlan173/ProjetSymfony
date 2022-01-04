<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/link')]
class LinkController extends AbstractController
{
    #[Route('/', name: 'link_index', methods: ['GET'])]
    public function index(LinkRepository $linkRepository): Response
    {
        return $this->render('link/index.html.twig', [
            'links' => $linkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'link_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $link->setScore(0);

            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('link/new.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'link_show', methods: ['GET'])]
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    #[Route('/{id}/edit', name: 'link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Link $link, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('link_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('link/edit.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'link_delete', methods: ['POST'])]
    public function delete(Request $request, Link $link, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $link->getId(), $request->request->get('_token'))) {
            $entityManager->remove($link);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/vote/{id}/{type}', name: 'link_vote', methods: ['GET'])]
    public function vote(LinkRepository $repo, int $id, String $type, EntityManagerInterface $entityManager): Response
    {

        // recuperer le link
        $link = $repo->find($id);

        if ($link->getUser()->contains($this->getUser())) {
            //deja vote 
            return $this->redirectToRoute('link_index', [], Response::HTTP_SEE_OTHER);
        } else {

            $score = $link->getScore();
            if ($type === '+') {
                $score++;
            } else {
                $score--;
            }

            $link->setScore($score);
            $link->addUser(($this->getUser()));

            $entityManager->persist($link);
            $entityManager->flush();
        }
        return $this->redirectToRoute('link_index', [], Response::HTTP_SEE_OTHER);
    }
}
