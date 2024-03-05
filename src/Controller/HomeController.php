<?php

namespace App\Controller;

use App\Entity\Locality;
use App\Entity\Reservation;
use App\Event\ReservationEvent;
use App\Repository\LocalityRepository;
use App\Service\DispatcherEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
    
    #[Route('/testeventpedro', name: 'test-event', methods: ['GET'])]
    public function testEvent(EventDispatcherInterface $dispatcher, DispatcherEvents $dispatcherEvents, EntityManagerInterface $entityManager)//: Response
    {
        $reservation = $entityManager->getRepository(Reservation::class)->find(25);
        $dispatcherEvents->dispatchReservation(['reservation'=>$reservation]);
        // $event = new ReservationEvent(['mi_dato'=>"soy un dato"]);
        // $dispatcher->dispatch($event, ReservationEvent::NAME);
        
        $localities = $entityManager->getRepository(Locality::class)->findAll();
        dump($localities);
        return $this->render('home/index.html.twig', [
            "localities" => $localities,
        ]);
    }

}
