<?php
// src/Controller/Api/ReportApi.php

namespace App\Controller\Api;

use App\Entity\Route;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\ReportService;
use App\Service\ReservationService;
use App\Service\TourService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use Symfony\Component\Serializer\SerializerInterface;

#[RouteAnnotation("/api/report", name: "report-")]
class ReportApi extends AbstractController
{
    public function __construct(
        private ReservationRepository $reservationRepository, 
        private SerializerInterface $serializer,
        private ReservationService $reservationService,
        private ReportService $reportService,
    ){}

    #[RouteAnnotation("/insert", name: "insert", methods: ["POST"])]
    public function insert(Request $request, ): Response
    {
        // Recoge los datos del formulario
        $formData = $request->request->all();

        // Recoge los datos del formulario
        $tour_id = $formData['tour_id'];
        $image = $request->files->get('image');
        $observation = $request->request->get('observation');
        $money = $request->request->get('money');
        $assistants = $formData['assistants'];
        $image = $request->files->get('image');

        // Acceder a los datos de las reservas una a una
        // $assistants = $request->request->get('assistants');
        // dd($formData['tour_id'], $formData);

        // Llamar al servicio 'Report service' que inserta los datos y devuelve los ID de las nuevas entidades creadas
            $newEntityId = $this->reportService->insert($tour_id, $image, $observation, $money);
            // $reservationService->sendMailReservation($newEntityId);

        // Llamar al servicio 'Reservation service' que inserta los datos y devuelve los ID de las nuevas entidades creadas
            $this->reservationService->updateArrAssistants($assistants);

        // Devolver una respuesta adecuada
        return new JsonResponse(['id' => $newEntityId], JsonResponse::HTTP_CREATED);
    }

}