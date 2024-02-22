<?php

namespace App\Controller;

use App\Entity\Locality;
use App\Repository\LocalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // private $params;

    // public function __construct(ParameterBagInterface $params)
    // {
    //     $this->params = $params;
    // }
    
    #[Route('/', name: 'landingpage', methods: ['GET'])]
    public function landingpage(EntityManagerInterface $entityManager, LocalityRepository $localityRepository): Response
    {
        $localities = $entityManager->getRepository(Locality::class)->findAll();
        dump($localities);
        return $this->render('home/index.html.twig', [
            "localities" => $localities,
        ]);
    }
    
    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(EntityManagerInterface $entityManager, LocalityRepository $localityRepository): Response
    {
        $localities = $entityManager->getRepository(Locality::class)->findAll();
        dump($localities);
        return $this->render('home/index.html.twig', [
            "localities" => $localities,
        ]);
    }
    

    
    

}
