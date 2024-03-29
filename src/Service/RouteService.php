<?php
// src/Service/RouteService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class RouteService
{
    private $entityManager;
    private $tourService;
    private $uploadsDir;
    private $nameUploadsDirDB;

    public function __construct(
        EntityManagerInterface $entityManager, 
        ParameterBagInterface $parameterBag,
        TourService $tourService
        )
    {
        $this->tourService = $tourService;
        $this->entityManager = $entityManager;
        $this->uploadsDir = $parameterBag->get('routeImgDir');
        $this->nameUploadsDirDB = $parameterBag->get('routeImgDirDB');
    }

    public function insertNewEntity(Request $request): int
    {
        // TODO Validar con evento y procesar los datos recibidos antes de insertar la nueva entidad Route

        // Recoger los datos del formulario
        $name = $request->request->get('name');
        $capacity = $request->request->get('capacity');
        $datetimeStart = $request->request->get('datetime_start');
        $datetimeEnd = $request->request->get('datetime_end');
        $description = $request->request->get('description');
        $programation = json_decode($request->request->get('programation'), true);
        $photo = $request->files->get('photo');
        $coordinates = json_decode($request->request->get('coordinates'), true);
        // $x = $coordinates['x'];
        // $y = $coordinates['y'];
        $selected_items = json_decode($request->request->get('selected_items'));
        // dd($request->request->all()); // debugg depurar

        // Save image on server
        $photoName = $this->saveUploadedFile($photo);


        // Crear una nueva entidad Route
        $route = new Route();
        $route->setName($name);
        $route->setDescription($description);
        $route->setPhoto($this->nameUploadsDirDB . $photoName);
        $route->setCoordinates(json_encode($coordinates));
        $route->setDatetimeStart(\DateTime::createFromFormat('d/m/Y H:i', $datetimeStart));
        $route->setDatetimeEnd(\DateTime::createFromFormat('d/m/Y H:i', $datetimeEnd));
        $route->setCapacity($capacity);
        $route->setProgramation(json_encode($programation));
        
        // Insert en la tabla "route_items"
        foreach ($selected_items as $itemId) {
            $item = $this->entityManager->getReference(Item::class, $itemId);
            $route->addItem($item);
        }

        // Persistir la entidad y guardar los cambios en la base de datos
        $this->entityManager->persist($route);
        $this->entityManager->flush();

        // Devolver el ID de la nueva entidad creada
        // dd($photoPath);
        return $route->getId();
    }

    public function insertNewEntityAndGenerateTours(Request $request): int
    {
        $route_id = $this->insertNewEntity($request);
        // $this->tourService->generateTours($route_id, json_decode($request->request->get('programation'), true));
        $this->tourService->generateTours($route_id);

        return $route_id;
    }

    public function update(Request $request): int|bool
    {
        // Recoger los datos del formulario
        $id = $request->request->get('id');
        $name = $request->request->get('name');
        $capacity = $request->request->get('capacity');
        $datetimeStart = $request->request->get('datetime_start');
        $datetimeEnd = $request->request->get('datetime_end');
        $description = $request->request->get('description');
        $programation = json_decode($request->request->get('programation'), true);
        $photo = $request->files->get('photo');
        $coordinates = json_decode($request->request->get('coordinates'), true);
        $selected_items = json_decode($request->request->get('selected_items'));


        // Buscar la entidad Route a actualizar
        $route = $this->entityManager->getRepository(Route::class)->find($id);
        if (!$route) { return false; } // Si no lo encuentro en base de datos => false
        $route->setName($name);
        $route->setDescription($description);
        if ($photo instanceof UploadedFile) {
            $photoName = $this->saveUploadedFile($photo); // Save image on server
            $route->setPhoto($this->nameUploadsDirDB . $photoName);
        } else {
            $route->setPhoto($request->request->get('photo'));
        }
        $route->setCoordinates(json_encode($coordinates));
        $route->setDatetimeStart(\DateTime::createFromFormat('d/m/Y H:i', $datetimeStart));
        $route->setDatetimeEnd(\DateTime::createFromFormat('d/m/Y H:i', $datetimeEnd));
        $route->setCapacity($capacity);
        if (is_array($programation) && !empty($programation)) {
            $route->setProgramation(json_encode($programation));
        }
        
        // Insert en la tabla "route_items"
        $route->getItem()->clear(); // Eliminar todos los items asociados a la ruta (para evitar duplicados)
        foreach ($selected_items as $itemId) {
            $item = $this->entityManager->getReference(Item::class, $itemId);
            $route->addItem($item);
        }

        // Persistir la entidad y guardar los cambios en la base de datos
        $this->entityManager->persist($route);
        $this->entityManager->flush();

        // Devolver el TRUE si no se ha devuelto false en ningun sitio anteriormente
        return true;
    }

    public function updateNewEntityAndGenerateTours(Request $request): int|bool
    {
        if (!$this->update($request)) {
            return false;
        }
        if (!$this->tourService->generateTours($request->request->get('id'))) {
            return false;
        }

        return $request->request->get('id');
    }

    // public function insertNewEntity(array $data, Request $request): int
    // {
    //     // TODO Validar con evento y procesar los datos recibidos antes de insertar la nueva entidad Route

    //     // Crear una nueva entidad Route
    //     $route = new Route();
    //     $route->setName($data['name']);
    //     $route->setDescription($data['description']);
    //     // $route->setPhoto($data['photo']);
    //     $route->setCoordinates(json_encode($data['coordinates']));
    //     $route->setDatetimeStart(\DateTime::createFromFormat('d/m/Y H:m', $data['datetime_start']));
    //     $route->setDatetimeEnd(\DateTime::createFromFormat('d/m/Y H:m', $data['datetime_end']));
    //     $route->setCapacity($data['capacity']);
    //     $route->setProgramation(json_encode($data['programation']));

    //     /*
    //     $file = $request->files->get('img');
    //     if ($file) {
    //         $fileName = uniqid() . '.' . $file->guessExtension();
    //         $file->move(
    //             $this->getParameter('img_dest_dir'),
    //             $fileName
    //         );
    //         $route->setPhoto($fileName);
    //     }
    //     */

    //     // Photo
    //     $file = $request->files->get('photo');
    //     if ($file) {
    //         if ($file instanceof UploadedFile) {
    //             $fileName = uniqid() . '.' . $file->guessExtension();
    //             $file->move($this->uploadsDir, $fileName);
    //             $route->setPhoto($fileName);
    //         }
    //     }
    //     dd($file);

    //     $selectedItemsData = $data['selected_items'];
    //     foreach ($selectedItemsData as $itemId) {
    //         $item = $this->entityManager->getReference(Item::class, $itemId);
    //         $route->addItem($item);
    //     }

    //     // Persistir la entidad y guardar los cambios en la base de datos
    //     $this->entityManager->persist($route);
    //     $this->entityManager->flush();

    //     // Devolver el ID de la nueva entidad creada
    //     return $route->getId();
    // }

    // Ejemplo de excepción personalizada
    // $startDate = \DateTime::createFromFormat('d/m/Y H:m', $data['datetime_start']);
    // if (!$startDate instanceof \DateTime) {
    //     // Manejar el caso en que la conversión de fecha falle
    //     throw new \InvalidArgumentException('Invalid start date format');
    // }
    // $route->setDatetimestart($startDate);

    
    // Function to save an image on server
    // private function saveUploadedFile($file)
    // {
    //     // $uploadsDirectory = $this->uploadsDir; // Directorio donde se guardará la imagen
    //     $uploadsDirectory = "/images/route"; // Directorio donde se guardará la imagen
    //     $filename = md5(uniqid()) . '.' . $file->guessExtension(); // Generar un nombre único para el archivo
    //     $file->move($uploadsDirectory, $filename); // Mover el archivo al directorio de uploads
    //     return $uploadsDirectory . '/' . $filename; // Devolver la ruta completa del archivo guardado
    // }

    public function saveUploadedFile($file)
    {
        $uploadsDirectory = $this->uploadsDir; // Directorio donde se guardará la imagen
        $filename = '/' . md5(uniqid()) . '.' . $file->guessExtension(); // Generar un nombre único para el archivo
        $targetPath = $uploadsDirectory . $filename; // Ruta completa de destino

        // Crear un objeto File para el archivo cargado
        $uploadedFile = new File($file->getPathname());

        // Crear un nuevo objeto Filesystem
        $filesystem = new Filesystem();

        // Intentar copiar el archivo cargado al directorio de uploads
        try {
            $filesystem->copy($uploadedFile->getPathname(), $targetPath);
        } catch (\Exception $e) {
            // Manejar cualquier error que pueda ocurrir durante la copia del archivo
            throw new \Exception('Error al guardar el archivo: ' . $e->getMessage());
        }

        // Devolver la ruta completa del archivo guardado
        // $uploadDirectoryRoutePhotoPath = trim(strpos($uploadsDirectory, '%'));
        // $routePhotoPath = $uploadDirectoryRoutePhotoPath . '/' . $filename;
        return $filename;
    }

}