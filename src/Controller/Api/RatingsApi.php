<?php
// src/Controller/Api/RatingsApi.php

namespace App\Controller\Api;

use App\Entity\Route;
use App\Repository\RouteRepository;
use App\Service\RouteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/route", name: "route-")]
class RatingsApi extends AbstractController
{
    private RouteRepository $routeRepository;
    private SerializerInterface $serializer;

    public function __construct(RouteRepository $routeRepository, SerializerInterface $serializer)
    {
        $this->routeRepository = $routeRepository;
        $this->serializer = $serializer;
    }

    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, RouteService $routeService): JsonResponse
    {
        // Obtener los datos en formato JSON
        $data = json_decode($request->getContent(), true);
        dd($data);
        // TODO validar y procesar los datos recibidos antes de insertar la nueva entidad Route

        // Llamar al servicio 'route_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $routeService->insertNewEntity($data, $request);

        // Devuelve una respuesta JSON con el ID de la nueva entidad creada y el código de estado HTTP 201 (Created)
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }

    #[RouteAnnotation("/insertBasic", name: "insert", methods: ["POST"])]
    public function insertBasic(Request $request, RouteService $routeService): Response
    {
        // Llamar al servicio 'route_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $routeService->insertNewEntity($request);

        // Devolver una respuesta adecuada
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }
}
