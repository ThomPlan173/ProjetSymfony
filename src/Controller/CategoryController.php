<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    #[Route("/addCategory", name: "add.category")]
    public function addCategory(): Response
    {
        $Category = new Category();
        $Category->setNom("NFSW");

        $em = $this->getDoctrine()->getManager();
        $em->persist($Category);
        $em->flush();

        return new Response("Category ajoutée");
    }
    /*
    #[Route("/Category/{id}", name: "show.category")]
    public function show(Category $category){

    }*/
    #[Route("/new", name: "new.category", methods: ["GET","POST"])]
    public function new(Request $request) : Response
    {
        $Category = new Category();
        $form = $this->createForm(CategoryType::class, $Category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Category);
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }
        
        return $this->render("/category/new.html.twig",[
            'category' => $Category,
            'form' => $form->createView(),
        ]);
    }
    #[Route("/{id}/edit", name: "edit.category", methods: ["GET","POST"])]
    public function edit(Request $request, int $id, CategoryRepository $categoryRepository) : Response
    {
        $category = $categoryRepository->find($id);
        $form = $this->createForm(Category::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute(('edit.category'), [
                'id' => $category->getId(),
            ]);
        }

        return $this->render("/category/edit",[
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }
}
