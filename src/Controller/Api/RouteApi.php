<?php
// src/Controller/Api/RouteApi.php

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
class RouteApi extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[RouteAnnotation("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(RouteRepository $routeRepository): Response
    {
        $routes = $routeRepository->findAll();
        $data = $this->serializer->serialize($routes, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/findById/{id}", name: "findById", methods: ["GET"])]
    public function findById($id, RouteRepository $routeRepository): Response
    {
        $route = $routeRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializer->serialize($route, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, RouteService $routeService): JsonResponse
    {
        // Obtener los datos en formato JSON
        $data = json_decode($request->getContent(), true);

        // TODO validar y procesar los datos recibidos antes de insertar la nueva entidad Route
        
        // Llamar al servicio 'route_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $routeService->insertNewEntity($data);

        // Devuelve una respuesta JSON con el ID de la nueva entidad creada y el código de estado HTTP 201 (Created)
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }

    #[RouteAnnotation("/update/{id}", name: "update", methods: ["PUT"])]
    public function update(Request $request, $id, RouteRepository $routeRepository): Response
    {
        $route = $routeRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        // Aquí puedes actualizar la entidad Route con los datos recibidos en $data
    }

    #[RouteAnnotation("/delete/{id}", name: "delete", methods: ["DELETE"])]
    public function delete($id, RouteRepository $routeRepository): Response
    {
        $entityManager = $routeRepository->getManager();
        $route = $entityManager->getRepository(Route::class)->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($route);
        $entityManager->flush();
        return new Response(null, Response::HTTP_OK);
    }
}
