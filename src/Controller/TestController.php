<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'prenom' => 'Thomas',
            'age' => 20,
            'week' => ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'],
            'date' => time()
        ]);
    }
    /*
    #[Route('/hello/{nom}/{age}', name: 'hello')]
    public function hello(String $nom = "testeur", int $age = 10) : Response
    {
        return new Response( $nom . '<br />' . $age ) ;
    } */
}
