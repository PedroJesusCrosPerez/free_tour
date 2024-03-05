<?php
// src/Service/ReservationService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Reservation;
use App\Entity\Route;
use App\Entity\Tour;
use App\Entity\User;
use App\Event\ReservationEvent;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private Security $security, 
        private MailService $mailService, 
        private ParameterBagInterface $params,
        private ValidatorInterface $validator,
        // private EventDispatcherInterface $dispatcher,
        private DispatcherEvents $dispatcher,
        private PdfService $pdfService,
    ){}

    public function insert(int $tour_id, int $number_tickets)//: bool
    {
        // Obtener datos
            $tour = $this->entityManager->getRepository(Tour::class)->find($tour_id); // Obtener el tour
            $user = $this->security->getUser(); // Obtener el usuario autenticado
            $datetime = new \DateTime('now'); // Obtener fecha y hora actual
        
        // Crear un nuevo objeto de la entidad 'Reservation'
            $reservation = (new Reservation())
                ->setClient($user)
                ->setTour($tour)
                ->setDatetime($datetime)
                ->setNumberTickets($number_tickets)
                ->setAssistants(null)
            ;
        
        // Lanzar evento / Dispatch event => validar datos
            $this->dispatcher->dispatchReservation(['reservation'=>$reservation]);

        // Guardar la nueva entidad en la base de datos
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

        // Generar pdf
            // $pdf = new PdfService('reservation', $this->params->get('pdf_reservation_path'));
            // $pdf->generatePdf($reservation);
            $pdf = $this->pdfService->generatePdfFromTemplate('/mailer/reservation-done-pdf.html.twig', ['reservation'=>$reservation]);

        // Enviar un correo electrónico al cliente
            $this->mailService->confirmReservation(
                // $user->getEmail(), 
                'pcroper1909@g.educaand.es', 
                'Reserva realizada', 
                'Reserva: '.$reservation->getClient()->getFormalName(),
                $pdf
            );

        // Devolver el ID de la nueva entidad creada
            return $reservation->getId();
    }

    public function update(int $tour_id, int $number_tickets)//: bool
    {
        // Obtener datos
            $tour = $this->entityManager->getRepository(Tour::class)->find($tour_id); // Obtener el tour
            $user = $this->security->getUser(); // Obtener el usuario autenticado
            $datetime = new \DateTime('now'); // Obtener fecha y hora actual

        // Crear un nuevo objeto de la entidad 'Reservation'
            $reservation = (new Reservation())
                ->setClient($user)
                ->setTour($tour)
                ->setDatetime($datetime)
                ->setNumberTickets($number_tickets)
                ->setAssistants(null)
            ;
        // dd($reservation);
        // Guardar la nueva entidad en la base de datos
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

        // Enviar un correo electrónico al cliente
            $this->mailService->sendMail($user->getEmail(), 'Reserva realizada', 'texto desde servicio de reserva');

        // Devolver el ID de la nueva entidad creada
            return $reservation->getId();
    }

    public function updateArrAssistants(array $reservations_assistants)//: bool
    {
        foreach ($reservations_assistants as $reservation_id => $assistants) {
            // Obtener datos
                $reservation = $this->entityManager->getRepository(Reservation::class)->find($reservation_id); // Obtener el tour
            
            // Crear un nuevo objeto de la entidad 'Reservation'
                $reservation->setAssistants($assistants);
            
            // Guardar la nueva entidad en la base de datos
                $this->entityManager->persist($reservation);
                $this->entityManager->flush();
        }

        // Devolver el ID de la nueva entidad creada
            // return $reservation->getId();
    }

    public function sendMailReservation(int $reservation_id)//: bool
    {
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($reservation_id);
        // Enviar un correo electrónico al cliente
            $this->mailService->sendMail($reservation->getClient()->getEmail(), 'Reserva realizada', 'texto desde servicio de reserva');

        return true;
    }

    public function getFormDataTours(int $route_id)//: bool
    {
        $route = $this->entityManager->getRepository(Route::class)->find($route_id);
        $tours = $route->getTours()->toArray();

        $formattedTours = [];
        foreach ($tours as $tour) {
            // Obtener la fecha y la hora del tour
            $datetime = $tour->getDatetime();
            // Formatear la fecha y la hora por separado
            $formattedDate = $datetime->format('Y-m-d');
            // $formattedDate = $datetime->format('d/m/Y');
            $formattedTime = $datetime->format('H:i');
            
            // Agregar la fecha y la hora formateadas al array resultante
            $formattedTours[] = [
                'id'=> $tour->getId(),
                'date' => $formattedDate, 
                'time' => $formattedTime
            ];
        }
        
        return $formattedTours;
    }


    public function insert2(User $user, Tour $tour, \DateTimeInterface $datetime, int $number_tickets, int $assistants = null)//: bool
    {
        $reservation = new Reservation();
        $reservation->setClient($user);
        $reservation->setTour($tour);
        $reservation->setDatetime($datetime);
        $reservation->setNumberTickets($number_tickets);
        $reservation->setAssistants($assistants);
        
        return $reservation;
    }

    

}