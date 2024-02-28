<?php
// src/Controller/Api/TourApi.php

namespace App\Controller\Api;

use App\Entity\Route;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\TourRepository;
use App\Service\SerializeService;
use App\Service\TourService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/tour", name: "tour-")]
class TourApi extends AbstractController
{
    public function __construct(
        private TourRepository $tourRepository, 
        private SerializeService $serializeService, 
        private SerializerInterface $serializer,
    ){}

    #[RouteAnnotation("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(): JsonResponse
    {
        $tours = $this->tourRepository->findAll();
        $serializedTours = $this->serializeService->serializeArrTour($tours, 'Level::BASIC');
        
        return new JsonResponse($serializedTours, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/findById/{id}", name: "findById", methods: ["GET"])]
    public function findById($id): Response
    {
        $route = $this->tourRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializer->serialize($route, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/generate", name: "generate", methods: ["POST"])]
    public function generate(Request $request, TourService $tourService): Response
    {
        $data = json_decode($request->getContent(), true);
    
        foreach ($data as $tourData) {
            // Obtener las fechas de inicio y fin del tour
            $dateStart = \DateTime::createFromFormat('d/m/Y', $tourData['date_start']);
            $dateEnd = \DateTime::createFromFormat('d/m/Y', $tourData['date_end']);
    
            // Obtener los días de la semana a verificar
            $daysToCheck = $tourData['pattern'];
    
            // Inicializar contador para la cantidad total de días seleccionados
            $totalCount = 0;
    
            // Iterar sobre cada día entre las fechas de inicio y fin
            $currentDate = clone $dateStart;
            while ($currentDate <= $dateEnd) {
                // Verificar si el día actual está en la lista de días a verificar
                $dayOfWeek = $currentDate->format('N');
                if (in_array($dayOfWeek, $daysToCheck)) {
                    $totalCount++;

                    // CREAR TOURS
                    // $datetimeTour = $dateStart . ' ' . $tourData['time_start'];
                    // $this->newtour($route, $datetimeTour, $guide, true);

                    // $datetimeString = $dateStart . ' ' . $tourData['time_start'];
                    // $datetimeTour = \DateTime::createFromFormat('Y-m-d H:i', $datetimeString);
                    // // $tour = new Tour(null, $datetimeTour, $guide, true);

                    dump($dateStart);
                    $time = explode(":", $tourData['time_start']);
                    $dateStart->setTime($time[0], $time[1]);
                    $dateStart->setTimezone(new \DateTimeZone('Europe/Madrid'));

                    dump($dateStart);
                    dd($tourData);
                }
    
                // Avanzar al siguiente día
                $currentDate->modify('+1 day');
            }
    
            // Imprimir la cantidad total de días seleccionados
            dump("Cantidad total de días seleccionados: " . $totalCount);
        }
    
        dd($data);
    }

    function newTour(Route $route, \DateTimeInterface $datetime, User $guide, bool $available) : Tour 
    {
        $tour = new Tour();
        $tour->setRoute($route);
        $tour->setGuide($guide);
        $tour->setDatetime($datetime);
        $tour->setAvailable($available);

        return new Tour();
    }

/*
    #[RouteAnnotation("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(): Response
    {
        $tours = $this->tourRepository->findAll();
        $data = $this->serializer->serialize($tours, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/findById/{id}", name: "findById", methods: ["GET"])]
    public function findById($id): Response
    {
        $tour = $this->tourRepository->find($id);
        if (!$tour) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializer->serialize($tour, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    // #[TourAnnotation("/insert", name: "insert", methods: ["POST"])]
    // public function insert(Request $request, TourService $tourService): JsonResponse
    // {
    //     // Obtener los datos en formato JSON
    //     $data = json_decode($request->getContent(), true);

    //     // TODO validar y procesar los datos recibidos antes de insertar la nueva entidad Tour

    //     // Llamar al servicio 'tour_service' que inserta los datos y devuelve el ID de la nueva entidad creada
    //     $newEntityId = $tourService->insertNewEntity($data, $request);

    //     // Devuelve una respuesta JSON con el ID de la nueva entidad creada y el código de estado HTTP 201 (Created)
    //     return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    // }

    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, TourService $tourService): Response
    {
        // Llamar al servicio 'tour_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $tourService->insertNewEntity($request);

        // Devolver una respuesta adecuada
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }

    #[RouteAnnotation("/update/{id}", name: "update", methods: ["PUT"])]
    public function update(Request $request, $id): Response
    {
        $tour = $this->tourRepository->find($id);
        if (!$tour) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        // Aquí puedes actualizar la entidad Tour con los datos recibidos en $data
    }

    #[RouteAnnotation("/delete/{id}", name: "delete", methods: ["DELETE"])]
    public function delete($id): Response
    {
        $entityManager = $this->tourRepository->getManager();
        $tour = $entityManager->getRepository(Tour::class)->find($id);
        if (!$tour) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($tour);
        $entityManager->flush();
        return new Response(null, Response::HTTP_OK);
    }
*/
}
