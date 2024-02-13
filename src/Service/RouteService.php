<?php
// src/Service/RouteService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class RouteService
{
    private $entityManager;
    private $uploadsDir;

    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;
        $this->uploadsDir = $parameterBag->get('routeImgDir');
    }

    public function insertNewEntity(array $data, Request $request): int
    {
        // TODO Validar con evento y procesar los datos recibidos antes de insertar la nueva entidad Route

        // Crear una nueva entidad Route
        $route = new Route();
        $route->setName($data['name']);
        $route->setDescription($data['description']);
        // $route->setPhoto($data['photo']);
        $route->setCoordinates(json_encode($data['coordinates']));
        $route->setDatetimeStart(\DateTime::createFromFormat('d/m/Y H:m', $data['datetime_start']));
        $route->setDatetimeEnd(\DateTime::createFromFormat('d/m/Y H:m', $data['datetime_end']));
        $route->setCapacity($data['capacity']);
        $route->setProgramation(json_encode($data['programation']));

        /*
        $file = $request->files->get('img');
        if ($file) {
            $fileName = uniqid() . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('img_dest_dir'),
                $fileName
            );
            $route->setPhoto($fileName);
        }
        */

        // Guardar
        $file = $request->files->get('photo');
        if ($file instanceof UploadedFile) {
            $fileName = uniqid() . '.' . $file->guessExtension();
            $file->move($this->uploadsDir, $fileName);
            $route->setPhoto($fileName);
        }

        $selectedItemsData = $data['selected_items'];
        foreach ($selectedItemsData as $itemId) {
            $item = $this->entityManager->getReference(Item::class, $itemId);
            $route->addItem($item);
        }

        // Persistir la entidad y guardar los cambios en la base de datos
        $this->entityManager->persist($route);
        $this->entityManager->flush();

        // Devolver el ID de la nueva entidad creada
        return $route->getId();
    }

    // Ejemplo de excepción personalizada
    // $startDate = \DateTime::createFromFormat('d/m/Y H:m', $data['datetime_start']);
    // if (!$startDate instanceof \DateTime) {
    //     // Manejar el caso en que la conversión de fecha falle
    //     throw new \InvalidArgumentException('Invalid start date format');
    // }
    // $route->setDatetimestart($startDate);

}