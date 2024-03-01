<?php
// src/Service/ReservationService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Report;
use App\Entity\Reservation;
use App\Entity\Route;
use App\Entity\Tour;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ReportService
{
    private $reportUploadDir;
    private $reportUploadDirDB;

    public function __construct(
        private EntityManagerInterface $entityManagerInterface, 
        private Security $security, 
        private MailService $mailService, 
        private ParameterBagInterface $params, 
        private SerializeService $serializeService, 

    ){
        $this->reportUploadDir = $params->get('reportImgDir');
        $this->reportUploadDirDB = $params->get('reportImgDirDB');
    }

    public function insert(int $tour_id, $file, string $observation, float $money)//: bool
    {
        // Obtener datos
            $tour = $this->entityManagerInterface->getRepository(Tour::class)->find($tour_id); // Obtener el tour
        
        // Guardar imágen
            $filename = $this->serializeService->saveFile($file, $this->reportUploadDir);
        // dump($file);
        

        // Crear un nuevo objeto de la entidad 'Report'
            $report = (new Report())
                ->setImage($this->reportUploadDirDB . $filename)
                ->setObservation($observation)
                ->setMoney($money)
                ->setTour($tour)
            ;
        // dd($report);

        // Guardar la nueva entidad en la base de datos
            $this->entityManagerInterface->persist($report);
            $this->entityManagerInterface->flush();

        // // Enviar un correo electrónico al cliente
        //     $this->mailService->sendMail($user->getEmail(), 'Reserva realizada', 'texto desde servicio de reserva');

        // Devolver el ID de la nueva entidad creada
            return $report->getId();
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

}