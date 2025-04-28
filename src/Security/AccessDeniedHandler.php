<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;


class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse // méthode est éxécutée dès que Symfony détecte une tentative d'accès interdite à une ressource protégée par un rôle ou une autorisation
    {
        // Redirige vers la page d'accueil ou une autre page de votre choix
        return new RedirectResponse('/'); // redirection vers la page d'accueil
    }
}