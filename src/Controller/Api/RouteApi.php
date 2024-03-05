<?php
// src/Controller/Api/RouteApi.php

namespace App\Controller\Api;

use App\Entity\Route;
use App\Repository\RouteRepository;
use App\Service\RouteService;
use App\Service\SerializeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/route", name: "route-")]
class RouteApi extends AbstractController
{
    public function __construct(
        private RouteRepository $routeRepository, 
        private RouteService $routeService, 
        private SerializerInterface $serializer,
        private SerializeService $serializeService,
    ){}

    #[RouteAnnotation("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(): Response
    {
        $routes = $this->routeRepository->findAll();
        $data = $this->serializer->serialize($routes, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/findById/{id}", name: "findById", methods: ["GET"])]
    public function findById($id): Response
    {
        $route = $this->routeRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializer->serialize($route, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/{id}", name: "id", methods: ["GET"])]
    public function id($id): JsonResponse
    {
        $route = $this->routeRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = $this->serializeService->serializeRoute($route, "Level::BASIC");
        return new JsonResponse($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"]), IsGranted("ROLE_GUIDE")]
    public function insert(Request $request, RouteService $routeService): Response
    {
        // Llamar al servicio 'route_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $routeService->insertNewEntity($request);

        // Devolver una respuesta adecuada
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
        // TODO por si no funciona crear ruta
        return $this->redirect('http://localhost:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CRouteCrudController');
    }

    #[RouteAnnotation("/insertAndGenerateTours", name: "insertAndGenerateTours", methods: ["POST"]), IsGranted("ROLE_GUIDE")]
    public function insertAndGenerateTours(Request $request, RouteService $routeService): Response
    {
        // Llamar al servicio 'route_service' que inserta los datos y devuelve el ID de la nueva entidad creada
        $newEntityId = $routeService->insertNewEntityAndGenerateTours($request);

        // Devolver una respuesta adecuada
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);

        // TODO por si no funciona crear ruta
        // return $this->redirectToRoute('admin');
        return $this->redirect('http://localhost:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CRouteCrudController');
    }

    #[RouteAnnotation("/update", name: "update", methods: ["POST"]), IsGranted("ROLE_GUIDE")]
    public function update(Request $request): Response
    {
        if (!$this->routeService->update($request)) 
        {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        return $this->redirect('http://localhost:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CRouteCrudController');
    }

    #[RouteAnnotation("/update/{id}", name: "updateById", methods: ["PUT"])]
    public function updateById(Request $request, $id): Response
    {
        $route = $this->routeRepository->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        // Aquí puedes actualizar la entidad Route con los datos recibidos en $data
    }

    #[RouteAnnotation("/delete/{id}", name: "delete", methods: ["DELETE"])]
    public function delete($id): Response
    {
        $entityManager = $this->routeRepository->getManager();
        $route = $entityManager->getRepository(Route::class)->find($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($route);
        $entityManager->flush();
        return new Response(null, Response::HTTP_OK);
    }

    #[RouteAnnotation("/existsByLocality/{id}", name: "updateByLocality", methods: ["GET"])]
    public function existsByLocality(Request $request, $id): Response
    {
        $route = $this->routeRepository->findByLocality($id);
        if (!$route) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request->getContent(), true);
        // Aquí puedes actualizar la entidad Route con los datos recibidos en $data
    }
}
