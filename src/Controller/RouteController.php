<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/route', name: 'route')]
class RouteController extends AbstractController
{
    #[Route('/', name: 'route-index')]
    public function index(): Response
    {
        return $this->render('route/index.html.twig');
    }
    
    #[Route('/create', name: 'route-create')]
    public function create(): Response
    {
        return $this->render('route/create.html.twig');
    }
}
