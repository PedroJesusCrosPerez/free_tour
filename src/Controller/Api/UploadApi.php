<?php
// src/Controller/Api/UploadApi.php

namespace App\Controller\Api;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api/upload", name: "upload-")]
class UploadApi extends AbstractController {
    #[Route("/create-route", name: "create-route", methods: ["GET"])]
    public function uploadCreateRoute(EntityManagerInterface $entityManager): JsonResponse {
        $items = $entityManager->getRepository(Item::class)->findAll();
        $guides = $entityManager->getRepository(User::class)->findByRoles(["ROLE_GUIDE"]);
        $itemsId = $this->getItemsId($items);

        $serializedItems = $this->serializeItems($items);
        $serializedGuides = $this->serializeGuides($guides);
        // $serializedItemsId = $this->serializeItemsId($itemsId);

        $data = [
            'items' => $serializedItems,
            'guides' => $serializedGuides,
            // 'itemsId' => $serializedItemsId,
        ];

        return $this->json($data, Response::HTTP_OK);
    }

    private function serializeProvince($province): array {
        return [
            'id' => $province->getId(),
            'name' => $province->getName(),
        ];
    }

    private function serializeLocality($locality): array {
        return [
            'name' => $locality->getName(),
            'province' => $this->serializeProvince($locality->getProvince()),
        ];
    }

    private function serializeItems(array $items): array {
        $serializedItems = [];
        foreach ($items as $item) {
            $serializedItems[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'photo' => $item->getPhoto(),
                'coordinates' => $item->getCoordinates(),
                'locality' => $this->serializeLocality($item->getLocality()),
            ];
        }
        return $serializedItems;
    }

    private function serializeGuides(array $guides): array {
        $serializedGuides = [];
        foreach ($guides as $guide) {
            $serializedGuides[] = [
                'id' => $guide->getId(),
                'name' => $guide->getName(),
                'surname' => $guide->getSurname(),
                'photo' => $guide->getPhoto(),
                'fullname' => $guide->getFullname(),
            ];
        }
        return $serializedGuides;
    }

    public function getItemsId($items): array {
        $itemIds = [];
        foreach ($items as $item) {
            $itemId = $item->getId(); // Asumiendo que el método getId() devuelve el ID del elemento
            // Verificar si el item_id ya ha sido agregado al array
            if (!in_array($itemId, $itemIds)) {
                // Si no ha sido agregado, agrégalo al array
                $itemIds[] = $itemId;
            }
        }
        return $itemIds;
    }

    public function serializeItemsId($itemIds): array {
        $serializedItems = [];
        foreach ($itemIds as $itemId) {
            $serializedItems[] = [
                'item_id' => $itemId,
            ];
        }
        return $serializedItems;
    }
    
    
    
}
