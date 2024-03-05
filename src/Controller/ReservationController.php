<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Reservation;
use App\Entity\Route as EntityRoute;
use App\Entity\Tour;
use App\Entity\User;
use App\Form\ReportType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    #[Route('/reserve/{route_id}', name: 'testreserve', methods: ['GET']), IsGranted("ROLE_CLIENT")]
    public function reserve(int $route_id): Response
    {
        $route = $this->entityManager->getRepository(EntityRoute::class)->find($route_id);
        $tours = $route->getTours()->toArray();

        $items = $route->getItem()->toArray()[0];
        $locality = $items->getLocality();

        // CREATE FORM
        return $this->render('forms/reservation.html.twig', [
            'route' => $route,
            'tours' => $tours,
            'locality' => $locality,
            'daysOfWeek' => json_decode($route->getProgramation())[0]->patternf,
            'items' => $route->getItem(),
        ]);
    }

    #[Route('/reservationList', name: 'my-reservation-list', methods: ['GET']), IsGranted("ROLE_CLIENT")]
    public function reservationList(): Response
    {
        $user = $this->security->getUser();

        // if ($user instanceof User && $user->getRoles() == ["ROLE_GUIDE"]) {
            $client_id = $user->getId();
            // $reservations = $this->entityManager->getRepository(Reservation::class)->findAllByClient_id($client_id);
            $reservations = $this->entityManager->getRepository(Reservation::class)->findAllWithRatingsByClient($client_id);
            
            // CREATE FORM
            return $this->render('role/client/reservation-list.html.twig', [
                'reservations' => $reservations,
            ]);
        // }
        // return $this->render('views/error.html.twig');
    }

    #[Route('/check-reserve/{reservation_id}', name: 'check-reserve', methods: ['GET']), IsGranted("ROLE_CLIENT")]
    public function checkReservation(int $reservation_id): Response
    {
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($reservation_id);
        
        // CREATE FORM
        return $this->render('role/client/check-reservation.html.twig', [
            'reservation' => $reservation,
        ]);
    }


    /*
    #[Route('/reserveea/{route_id}', name: 'reserve', methods: ['GET'])]
    public function reserveea(Request $request, int $route_id): Response
    {
        // // PROCESSING FORM
        // $reservation = new Reservation();
        // $form = $this->createForm(ReservationType::class, $reservation);
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     // $form->getData() holds the submitted values
        //     // but, the original `$task` variable has also been updated
        //     $reservation = $form->getData();

        //     // ... perform some action, such as saving the task to the database

        //     return $this->redirectToRoute('task_success');
        // }


        // CREATE FORM
        $route = $this->entityManager->getRepository(EntityRoute::class)->find($route_id);

        // $form = $this->createForm(ReservationType::class, null, [
        //     'route' => $route,
        // ]);
        // return $this->render('forms/reservation.html.twig', [
        //     'reservationForm' => $form->createView(),
        //     'route_id' => $route_id,
        // ]);

        // $idRutaTuristica = $request->getRequestUri();
        // $idRutaTuristica = 67;
        // $tours = $route->getTours()->toArray();
        $tours = array("Prueba", "prueba22");
        dump($tours);
        $form2 = $this->createForm(ReservationType::class, null, [
            'tours' => $tours,
        ]);
        return $this->render('forms/reservationea.html.twig', [
            'reservationForm' => $form2->createView(),
        ]);
    }


    #[Route("/reservation/new/{routeId}", name: "report_new", methods: ['GET'])]
    public function new(Request $request, $routeId): Response
    {
        // Retrieve tours for the specified route
        $route = $this->entityManager->getRepository(EntityRoute::class)->find($routeId);
        $tours = $route->getTours();

        // Create a new Reservation instance
        $report = new Reservation();

        // Create the report form, passing tours as options
        $form = $this->createForm(ReportType::class, $report, [
            'tours' => $tours,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission
            // ...
        }

        return $this->render('form/report.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    */
}
