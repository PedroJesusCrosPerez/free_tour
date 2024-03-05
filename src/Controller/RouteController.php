<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\RouteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/route', name: 'route-')]
class RouteController extends AbstractController
{
    public function __construct(private RouteRepository $routeRepository) {}
    
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('route/index.html.twig');
    }
    
    #[Route('/create', name: 'create')]
    public function create(ItemRepository $itemRepository): Response
    {
        return $this->render('route/create.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }
    
    #[Route('/search', name: 'search', methods: ['GET', 'POST'])] // No funciona con POST, PREGUNTAR
    public function search(Request $request): Response
    {
        // Obtener un parámetro específico de la consulta GET
        $locality = $request->query->get('locality');
        $daterange = $request->query->get('daterange');
        $amount = $request->query->get('amount');
        $saveSearch = $request->query->get('saveSearch');

        // Obtener todos los parámetros de la consulta GET
        $allParams = $request->query->all();

        // Dividir el rango de fechas en date_start y date_end
        list($date_start, $date_end) = explode(' / ', $daterange);
        $date_start = explode('/', $date_start);
        $date_end = explode('/', $date_end);

        $datetime_start = new \DateTime();
        $datetime_start->setDate($date_start[2], $date_start[1], $date_start[0]);
        
        $datetime_end = new \DateTime();
        $datetime_end->setDate($date_end[2], $date_end[1], $date_end[0]);


        // // Formatear las fechas en el formato Y-m-d
        // $date_start_formatted = $date_start[2] . '-' . $date_start[1] . '-' . $date_start[0];
        // $date_end_formatted = $date_end[2] . '-' . $date_end[1] . '-' . $date_end[0];

        // return $this->render('route/search.html.twig', [
        //     'locality' => $locality,
        //     // 'daterange' => $daterange,
        //     'date_start' => $date_start_formatted,
        //     'date_end' => $date_end_formatted,
        //     // 'amount' => $amount,
        //     // 'saveSearch' => $saveSearch,
        //     // 'allParams' => $allParams
        // ]);

        $routes = $this->routeRepository->findByDateRange($datetime_start, $datetime_end);
        return $this->render('route/list.html.twig', [
            'routes' => $routes,
        ]);
    }
    
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(RouteRepository $routeRepository): Response
    {
        // $reservations = $this->entityManager->getRepository(Reservation::class)->findAll();
        // dump($reservations[2]->getRatings()[0]->getGuideRating());
        // dump($reservations);
        // $reservation = $this->entityManager->getRepository(Reservation::class)->find(3);
        // dump($reservation->getRatings()[0]->getGuideRating());

        $routes = $routeRepository->findAll();
        // dump($routeRepository->getRatings(26));
        foreach ($routes as $route) {
            $ratings = $routeRepository->getRatings($route->getId());
            $media = 0;
            if ($ratings != []) {
                foreach ($ratings as $rating) {
                    $media += $rating['route_rating'];
                }
                $route->setAverageRating($media / count($ratings));
                $route->setCountRating(count($ratings));
            } else {
                // dump("La ruta " . $route->getId() . " no tiene valoraciones");
            }
        }
        return $this->render('route/list.html.twig', [
            'routes' => $routes,
        ]);
    }
}
