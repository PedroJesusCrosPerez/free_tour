<?php
// src/Service/TourService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Route;
use App\Entity\Tour;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class TourService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateTours(int $route_id)//: bool
    {
        $route = $this->entityManager->getRepository(Route::class)->find($route_id);
        $programation = json_decode($route->getProgramation()); // TODO esto se podría cambiar en los setters de Route
        
        foreach ($programation as $tourData) {
            // Obtener las fechas de inicio y fin del tour
            $dateStart = \DateTime::createFromFormat('d/m/Y', $tourData->date_start);
            $dateEnd = \DateTime::createFromFormat('d/m/Y', $tourData->date_end);
    
            // Obtener los días de la semana a verificar
            $daysToCheck = $tourData->pattern;
    
            // Inicializar contador para la cantidad total de días seleccionados
            $totalCount = 0;
    
            // Iterar sobre cada día entre las fechas de inicio y fin
            $currentDate = clone $dateStart;
            while ($currentDate <= $dateEnd) {
                // Verificar si el día actual está en la lista de días a verificar
                $dayOfWeek = $currentDate->format('N');
                if (in_array($dayOfWeek, $daysToCheck)) {
                    $totalCount++;

                    // Datetime
                    $time = explode(":", $tourData->time_start);
                    $currentDate->setTime($time[0], $time[1]);
                    $currentDate->setTimezone(new \DateTimeZone('Europe/Madrid'));

                    // Guide
                    $guide = $this->entityManager->getRepository(User::class)->find($tourData->guide->id);

                    // CREAR TOURS
                    $tour = new Tour();
                    $tour->setDatetime($currentDate);
                    $tour->setAvailable(true);
                    $tour->setRoute($route);
                    $tour->setGuide($guide);
                    
                    $route->addTour($tour);

                    // Persistir
                    $this->entityManager->persist($tour);
                    $this->entityManager->flush();
                }
    
                // Avanzar al siguiente día
                $currentDate->modify('+1 day');
            }
    
            // Imprimir la cantidad total de días seleccionados
            // dump("Cantidad total de días seleccionados: " . $totalCount);

        }
    }

}