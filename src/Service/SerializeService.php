<?php
// src/Service/SerializeService.php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Locality;
use App\Entity\Province;
use App\Entity\Route;
use App\Entity\Tour;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SerializeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ){}

    public function serializeArrTour(array $arrTour)//: JsonResponse
    {
        // Serialize tours to JSON format
        $data = [];
        foreach ($arrTour as $tour) {
            $data[] = $this->serializeTour($tour, 'Level::BASIC');
        }
        
        // return new JsonResponse($data);
        return $data;
    }

    public function serializeTour(Tour $tour, $level=""): array
    {
        switch ($level) {
            case 'Level::BASIC':
                // Serialize tour to JSON format
                return [
                    'id' => $tour->getId(),
                    'datetime' => $tour->getDatetime()->format('Y-m-d H:i:s'),
                    'route' => $tour->getRoute()->getName(),
                    'guide' => $tour->getGuide() ? $tour->getGuide()->getName() : null,
                    'number_tickets' => count($tour->getReservations()),
                    'capacity' => $tour->getRoute()->getCapacity(),
                    'available' => $tour->isAvailable(),
                ];
                break;
            
            default:
                return ["result"=>"TO IMPLEMENT"];
                break;
        }
    }

    public function serializeProvince(Province $province): array {
        return [
            'id' => $province->getId(),
            'name' => $province->getName(),
        ];
    }

    public function serializeLocality(Locality $locality): array {
        return [
            'id' => $locality->getId(),
            'name' => $locality->getName(),
            'province' => $this->serializeProvince($locality->getProvince()),
        ];
    }

    public function serializeArrItem(array $items): array {
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

    public function serializeArrUser(array $arrUser, $format=""): array {
        $serializedUsers = [];

        switch ($format) 
        {
            case 'Format::ASSOCIATIVE':
                foreach ($arrUser as $user) {
                    $serializedUsers[$user->getId()] = [
                        'name' => $user->getName(),
                        'surname' => $user->getSurname(),
                        'photo' => $user->getPhoto(),
                        'fullname' => $user->getFullname(),
                    ];
                }
                break;
            
            default:
                foreach ($arrUser as $user) {
                    $serializedUsers[] = [
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                        'surname' => $user->getSurname(),
                        'photo' => $user->getPhoto(),
                        'fullname' => $user->getFullname(),
                    ];
                }
                break;
        }
        
        return $serializedUsers;
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