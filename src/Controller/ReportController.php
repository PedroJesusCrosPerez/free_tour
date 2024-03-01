<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Tour;
use App\Form\ReportType;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/report', name: 'report-')]
class ReportController extends AbstractController
{
    public function __construct(
        private ReportRepository $reportRepository,
        private EntityManagerInterface $entityManagerInterface,
        private Security $security,
    ) {}
    
    
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $actualGuide = $this->security->getUser();
        $tours = $actualGuide->getTours();

        return $this->render('role/guide/report-tour-list.html.twig', [
            'tours' => $tours,
        ]);
    }
    
    
    #[Route('/do/{tour_id}', name: 'do', methods: ['GET'])]
    public function do(Request $request, $tour_id): Response
    {
        $actualGuide = $this->security->getUser();
        $tour = $this->entityManagerInterface->getRepository(Tour::class)->find($tour_id);
        // dd($tours, $tours[0]);
        $reservations = $tour->getReservations();

        return $this->render('role/guide/report.html.twig', [
            'reservations' => $reservations,
            // 'tour' => $tour,
            'tour_id' => $tour_id,
        ]);
    }
    
    #[Route('/send', name: 'send', methods: ['GET'])]
    public function send(Request $request): Response
    {
        // Recoge los datos del formulario
        $formData = $request->request->all();

        // Recoge los datos del formulario
        $tour_id = $request->files->get('tour_id');
        $image = $request->files->get('image');
        $observation = $request->request->get('observation');
        $money = $request->request->get('money');

        // Acceder a los datos de las reservas una a una
        $assistants = $request->request->get('assistants');

        // foreach ($assistants as $reservationData) {
        //     // Acceder a los datos individuales de cada reserva
        //     $reservationId = $reservationData['id'];
        //     $numberOfTickets = $reservationData['numberOfTickets'];
        //     // Hacer lo que necesites con los datos de cada reserva
        // }

        // Utiliza dd() para imprimir los datos y detener la ejecución
        dd(
            $tour_id,
            $image,
            $observation,
            $money,
            $assistants,
            $formData,
        );
    }
    
    // TO DO FORM TYPE
    #[Route('/new', name: 'new', methods: ['GET'])]
    public function new(Request $request): Response
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Procesar los datos aquí, por ejemplo, guardar en la base de datos
            $this->entityManagerInterface->persist($report);
            $this->entityManagerInterface->flush();

            // Redirigir a alguna página después de procesar los datos
            return $this->redirectToRoute('home');
        }

        return $this->render('report/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
