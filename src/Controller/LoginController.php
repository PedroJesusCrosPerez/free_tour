<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'form-login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/_login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    
    #[Route('/logindefault', name: 'action-login')]
    public function default(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // return $this->render('login/index.html.twig', [
            return $this->render('login/default.html.twig', [
        // return $this->render('PRUEBAS/prueba_login_modal.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    // TODO este código es para redirigir al usuario según su rol
    // #[Route("/after-login", name:"app_after_login")]
    // public function afterLogin()
    // {
    //     // Redirige al usuario según su rol
    //     if ($this->isGranted('ROLE_ADMIN')) {
    //         return $this->redirectToRoute('admin_dashboard');
    //     } elseif ($this->isGranted('ROLE_CLIENT')) {
    //         return $this->redirectToRoute('client_dashboard');
    //     } elseif ($this->isGranted('ROLE_GUIDE')) {
    //         return $this->redirectToRoute('guide_dashboard');
    //     }

    //     // Maneja otros casos según tus necesidades
    //     return $this->redirectToRoute('app_default_route');
    // }
}
