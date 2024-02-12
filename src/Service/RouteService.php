<?php
// src/Service/RouteService.php

namespace App\Service;

use App\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;

class RouteService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insertNewEntity(array $data): int
    {
        // TODO validar y procesar los datos recibidos antes de insertar la nueva entidad Route
        $route = new Route();
        $route->setName($data['name']);
        $route->setDescription($data['description']);
        $route->setPhoto($data['photo']);
        $route->setCoordinates( json_encode($data['coordinates']) );
        $route->setDatetimestart($data['datetime_start']);
        $route->setDatetimeEnd($data['datetime_end']);
        $route->setCapacity($data['capacity']);
        $route->setProgramation($data['programation']);

        // TODO insert en la tabla Route_has_items
        // $data['selected_items']
        foreach ($data['items'] as $item) {
            $route->addItem($item);
        }

        // Persiste la entidad y guarda los cambios en la base de datos
        $this->entityManager->persist($route);
        $this->entityManager->flush();

        // Devuelve el ID de la nueva entidad creada
        return $route->getId();
    }
}