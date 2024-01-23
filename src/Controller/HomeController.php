<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        $mivariableglobal = $this->params->get('project_root');

        return $this->render('home/index.html.twig', [
            // 'controller_name' => 'HomeController',
            'controller_name' => $mivariableglobal,
        ]);
    }
}
