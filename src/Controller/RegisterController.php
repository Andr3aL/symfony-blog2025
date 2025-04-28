<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager): Response // on fait une requete de dépendance sur la classe Request (mécanique d'injection de dépendance)
    // le mécanisme d'injection de dépendance en symfony => il s'agit de dire au symfony aue je veux que tu rentre dans cette action en ambarquant avec toi l'objet $request qui est une instance stocké dans une variable
     // Il faut que mon formulaire écoute et analyse la requete qui vient de la vue et vérifier s'il y a un post envoyé ou pas 
        // On utilise l'objet request créé par symfony et qui représente la requete HTTP entrante (ici la requete contient des données de formulaire)
        // cette méthode est utilisée pour traiter les données soumises par l'utilisateur
    {


    if ($this->getUser()) {
        
        return $this->redirectToRoute('app_account');
        // if the user is already logged in, redirect to account page
    }


    $user = new Users; // On crée un objet de la classe Users et on le stock dans la variable $user
    $form = $this->createForm(RegisterType::class, $user); // on crée le formulaire avec la classe RegisterType
    // cette méthode prend en paramètres : la classe du formulaire et l'objet géré par le formulaire


        $form->handleRequest($request); // on gère la requête avec le formulaire

        if ($form->isSubmitted() && $form->isValid()) {
            // il faut hasher le mpd
            $password = $form->get('password')->getData(); // on récupère le mot de passe
            $passwordHasher = $userPasswordHasherInterface->hashPassword($user, $password); // on hash le mot de passe
            $user->setPassword($passwordHasher); // on set le mot de passe hashé dans l'objet user

            $entityManager->persist($user); // on persiste l'objet user
            // persist() : permet de dire à Doctrine que l'on veut ajouter cet objet dans la base de données
            // persist = 'prepare' en sql classique
            $entityManager->flush(); // on enregistre les données dans la base de données
            // flush = 'execute' en sql classique


            // $message = $this->addFlash('success', 'message success'); // on ajoute un message flash
            return $this->redirectToRoute('app_login');
            // on redirige vers la page login

        }

        return $this->render('register/register.html.twig', [
            'formInscription' => $form->createView(), // 'formInscription' c'est le nom de la variable que l'on va utiliser dans le twig
            // 'message' => $message
        ]);
    }
}
