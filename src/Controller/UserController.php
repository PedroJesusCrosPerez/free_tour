<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user-')]
class UserController extends AbstractController
{
    // #######################################################################################
    // ######################################## GUIDE ########################################
    // #######################################################################################

    // Guide calendar tours
    #[Route('/guide', name: 'guide-calendar', methods: ['GET'])]
    public function guideDashboard(Security $security): Response
    {
        $user = $security->getUser();
        $tours = $user->getTours();
        return $this->render('role/guide/calendar.html.twig', [
            'tours' => $tours,
        ]);
    }



    
    // #######################################################################################
    // ######################################## CLIENT ########################################
    // #######################################################################################
}
