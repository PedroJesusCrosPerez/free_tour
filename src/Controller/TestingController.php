<?php

namespace App\Controller;

use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestingController extends AbstractController
{
    // private $params;

    // public function __construct(ParameterBagInterface $params)
    // {
    //     $this->params = $params;
    // }
    
    #[Route('/testing', name: 'testing')]
    public function testing(MessageGenerator $messageGenerator): Response
    {
        return new Response($messageGenerator->notifyOfSiteUpdate("pepe@gmail.es"));
    }
}
