<?php
// src/Service/RatingsService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Ratings;
use App\Entity\Reservation;
use App\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class RatingsService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function insertNewEntity(Request $request): int
    {
        // TODO Validar con evento y procesar los datos recibidos antes de insertar la nueva entidad Route

        // Recoger los datos del formulario
        $reservation_id = $request->request->get('reservation_id');
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($reservation_id);
        $tour_rating = $request->request->get('tour_rating');
        $guide_rating = $request->request->get('guide_rating');
        $comments = $request->request->get('comments');

        // Crear una nueva entidad Ratings
        $rating = new Ratings();
        $rating->setReservation($reservation);
        $rating->setRouteRating($tour_rating);
        $rating->setGuideRating($guide_rating);
        $rating->setComments($comments);
        
        // Persistir la entidad y guardar los cambios en la base de datos
        $this->entityManager->persist($rating);
        $this->entityManager->flush();

        // Devolver el ID de la nueva entidad creada
        return $rating->getId();
    }

}