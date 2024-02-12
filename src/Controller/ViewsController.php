<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewsController extends AbstractController
{
    #[Route('/views', name: 'app_views')]
    public function index(): Response
    {
        return $this->render('views/index.html.twig', [
            'controller_name' => 'ViewsController',
        ]);
    }

    
    // #[Route('/views', name: 'app_views')]
    // public function index(): Response
    // {
    //     return $this->render('views/index.html.twig', [
    //         'controller_name' => 'ViewsController',
    //     ]);
    // }

    
    #[Route('/access', name: 'access', methods: ['GET'])]
    public function access(): Response
    {
        // return $this->render('views/access.html.twig');
        return $this->render('login/forms.html.twig');
    }
}
