<?php
// src/Controller/Api/ReservationApi.php

namespace App\Controller\Api;

use App\Entity\Route;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use App\Service\TourService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/reservation", name: "reservation-")]
class ReservationApi extends AbstractController
{
    private ReservationRepository $reservationRepository;
    private SerializerInterface $serializer;

    public function __construct(ReservationRepository $reservationRepository, SerializerInterface $serializer)
    {
        $this->reservationRepository = $reservationRepository;
        $this->serializer = $serializer;
    }

    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, ReservationService $reservationService): Response
    {
        // Obtener los datos del formulario
            $tour_id = $request->request->get('tour_id'); // Obtener el ID del tour
            $tickets = $request->request->get('tickets'); // Obtener el número de tickets

        // Llamar al servicio 'Reservation service' que inserta los datos y devuelve el ID de la nueva entidad creada
            $newEntityId = $reservationService->insert($tour_id, $tickets);
        // Enviar mail con la información
            $reservationService->sendMailReservation($newEntityId);

        // Devolver respuesta con id del nuevo registro
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }

    #[RouteAnnotation("/getFormData", name: "getFormDatas", methods: ["GET"])]
    public function getFormData(Request $request, ReservationService $reservationService): Response
    {
        // Obtener el valor del parámetro 'id' de la URL
        $id = $request->query->get('id');

        $tours = $reservationService->getFormDataTours($id);

        // Devolver una respuesta adecuada
        return new JsonResponse(['tours' => $tours], JsonResponse::HTTP_CREATED);
    }

    

/*

    #[RouteAnnotation("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(): Response
    {
        $routes = $this->reservationRepository->findAll();
        $data = $this->serializer->serialize($routes, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/findById/{id}", name: "findById", methods: ["GET"])]
    public function findById($id): Response
    {
        $route = $this->reservationRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializer->serialize($route, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/update/{id}", name: "update", methods: ["PUT"])]
    public function update(Request $request, $id): Response
    {
        $route = $this->reservationRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        // Aquí puedes actualizar la entidad Route con los datos recibidos en $data
    }

    #[RouteAnnotation("/delete/{id}", name: "delete", methods: ["DELETE"])]
    public function delete($id): Response
    {
        $entityManager = $this->reservationRepository->getManager();
        $route = $entityManager->getRepository(Route::class)->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($route);
        $entityManager->flush();
        return new Response(null, Response::HTTP_OK);
    }

*/

}