<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MessageGenerator;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/testing/repository', name: 'repository')]
    public function repository(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findByRoles(array('ROLE_GUIDE'));
        
        return $this->render('testing/repositories.html.twig', [
            "entityname" => "User",
            "items" => $users,
        ]);
    }
}
