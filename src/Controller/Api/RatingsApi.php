<?php
// src/Controller/Api/RatingsApi.php

namespace App\Controller\Api;

use App\Service\RatingsService;
use App\Service\RouteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/rating", name: "rating-")]
class RatingsApi extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private RatingsService $ratingsService
    ){}


    // #[RouteAnnotation("/findBy/{route_id}", name: "insert", methods: ["GET"])]
    // public function findBy(Request $request, $route_id): JsonResponse
    // {}


    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request): JsonResponse
    {
        // TODO validar y procesar los datos recibidos antes de insertar la nueva entidad Route

        // Llamar al servicio 'route_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $this->ratingsService->insertNewEntity($request);

        // Devuelve una respuesta JSON con el ID de la nueva entidad creada y el código de estado HTTP 201 (Created)
        return new JsonResponse(['success' => true, 'id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }
}
