<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/route', name: 'route-')]
class RouteController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('route/index.html.twig');
    }
    
    #[Route('/create', name: 'create')]
    public function create(): Response
    {
        return $this->render('route/create.html.twig');
    }
    
    #[Route('/search', name: 'search', methods: ['GET', 'POST'])] // No funciona con POST, PREGUNTAR
    public function search(Request $request): Response
    {
        // Obtener un parámetro específico de la consulta GET
        $locality = $request->query->get('locality');
        $daterange = $request->query->get('daterange');
        $amount = $request->query->get('amount');
        $saveSearch = $request->query->get('saveSearch');

        // Obtener todos los parámetros de la consulta GET
        $allParams = $request->query->all();
        
        return $this->render('route/search.html.twig', [
            'locality' => $locality,
            'daterange' => $daterange,
            'amount' => $amount,
            'saveSearch' => $saveSearch,
            'allParams' => $allParams
        ]);
    }
}
