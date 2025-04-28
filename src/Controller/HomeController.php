<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    // /home c'est le chemin (url) ; name: c'est le nom de la route
    public function index(): Response
    {

    // "/" => le chemin de cette route
    // "app_home" => le nom de la route qui nous servira au moment de l'appelle des routes dans twig

        return $this->render('home/home.html.twig', [ // la fonction index nous renvoie la vue avec un tableau clé valeur
            // templates/home/index.html.twig c'est le chemin de la vue = home/index.html.twig dans le render, ici (car il sait deja où il est)
            'controller_name' => 'HomeController',
        ]);
    }
}
