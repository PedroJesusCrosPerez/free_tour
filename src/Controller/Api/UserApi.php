<?php
// src/Controller/Api/TourApi.php

namespace App\Controller\Api;

use App\Entity\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SerializeService;
use App\Service\TourService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/user", name: "user-")]
class UserApi extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository, 
        private SerializerInterface $serializer,
        private SerializeService $seriailizeService,
        // private Request $request,
    ){}

    #[RouteAnnotation("/findAll", name: "findAll", methods: ["GET"])]
    public function findAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $serializedUsers = $this->seriailizeService->serializeArrUser($users, 'Format::ASSOCIATIVE');
        
        return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/findAll/{format}", name: "findAll-format", methods: ["GET"])]
    public function findAllFormat(string $format="", Request $request): JsonResponse
    {
        // Comprueba si existe la variable role en la url
        if ($request->query->has('role')) {
            $role = $request->query->get('role');
            $users = $this->userRepository->findByRoles([$role]);

            $serializedUsers = $this->seriailizeService->serializeArrUser($users, "Format::".$format);
            return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        $users = $this->userRepository->findAll();
        $serializedUsers = $this->seriailizeService->serializeArrUser($users, "Format::".$format);
        
        return new JsonResponse($serializedUsers, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[RouteAnnotation("/getTours", name: "getTours", methods: ["GET"])]
    public function getTours(Security $security): JsonResponse
    {
        $user = $security->getUser();
        $tours = $this->serializeTours( $user->getTours()->toArray() );
        // $tours = $user->getTours()->toArray();
        
        // $data = $this->serializer->serialize($tours, 'json');
        return $this->json($tours, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    private function serializeTours(array $tours): array {
        $serializedTours = [];
        foreach ($tours as $tour) {
            $serializedTours[] = [
                'id' => $tour->getId(),
                'route' => $this->serializeRoute($tour->getRoute()),
                'date' => $tour->getDate(),
                'time' => $tour->getTime(),
                'datetime' => $tour->getDatetimeFormatedForFullCalendar(),
                // 'locality' => $this->serializeLocality($item->getLocality()),
            ];
        }
        return $serializedTours;
    }

    private function serializeRoute($route): array {
        return [
            'id' => $route->getId(),
            'name' => $route->getName(),
            'description' => $route->getDescription(),
            'photo' => $route->getPhoto(),
            // 'coordinates' => $route->getCoordinates(),
            // 'datetime_start' => $route->getDatetime_start(),
            // 'datetime_end' => $route->getDatetime_end(),
        ];
    }

}