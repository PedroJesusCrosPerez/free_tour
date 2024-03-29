<?php
namespace App\Controller\Admin;

use App\Entity;
use App\Entity\Item;
use App\Entity\Locality;
use App\Entity\Province;
use App\Entity\Ratings;
use App\Entity\Report;
use App\Entity\Reservation;
use App\Entity\Route as EntityRoute;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\ItemRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
// use App\Entity\Route;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();
        $this->configureDashboard();
        $this->configureMenuItems();
        $this->configureActions();
        // $this->configureUserMenu();
        return $this->render('admin/index.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Free Tour');
    }
    
    // NOTA si esto lo cambias a RouteController.php o a HomeController.php
    // en algunos funciona y en otros no
    // además de ir alternando comentar las líneas => // Pruebas para formulario personalizado - START
    #[Route('/prueba', name: 'prueba')]
    public function prueba(): Response
    {
        return $this->render('admin/create-route.html.twig');
    }

    
    //C:\Users\pedro\symfony\free_tour\vendor\easycorp\easyadmin-bundle\src\Resources\views\page\create-route.html.twig
    #[Route('/prueba2', name: 'prueba2')]
    public function prueba2(): Response
    {
        return $this->render('@EasyAdmin/page/create-route.html.twig');
    }

    #[Route('/create-route', name: 'create-route')]
    public function createRoute(ItemRepository $itemRepository, UserRepository $userRepository): Response
    {
        $items = $itemRepository->findAll();
        $guides = $userRepository->findByRoles(array('ROLE_GUIDE'));
    

        // return $this->render('route/create.html.twig', [
        //     'items' => $items,
        //     'guides' => $guides,
        // ]);
        return $this->render('route/create.html.twig');
    }

    #[Route('/manage-tours', name: 'manage-tours')]
    public function manageTours(ItemRepository $itemRepository, UserRepository $userRepository): Response
    {
        $items = $itemRepository->findAll();
        $guides = $userRepository->findByRoles(array('ROLE_GUIDE'));
        

        // return $this->render('route/create.html.twig', [
        //     'items' => $items,
        //     'guides' => $guides,
        // ]);
        return $this->render('route/manage-tours.html.twig');
    }

    #[Route("/getReservationGraph", name: "adminGraph-reservation", methods: ["GET"])]
    public function getReservationGraph(Request $request, ReservationRepository $reservationRepository): Response
    {
        return $this->render('route/chart.html.twig');
    }

    #[Route('/edit-route', name: 'edit-route')]
    public function editarRuta(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository/*, $id*/): Response
    {
        $id = $request->query->get('id');
        $route = $entityManager->getRepository(EntityRoute::class)->find($id);
        // dd(json_decode($route->getProgramation()));
        return $this->render('route/edit.html.twig', [
            'id' => $id,
            'route' => $route,
            'programations' => json_decode($route->getProgramation()),
            'localities' => $entityManager->getRepository(Locality::class)->findAll(),
            'guides' => $entityManager->getRepository(User::class)->findByRoles(['ROLE_GUIDE'])
        ]);
    }

    #[Route('/edit-programation', name: 'edit-programation')]
    public function editarProgramationRuta(EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository/*, $id*/): Response
    {
        $id = $request->query->get('id');
        $route = $entityManager->getRepository(EntityRoute::class)->find($id);
        // dd(json_decode($route->getProgramation()));
        return $this->render('route/edit-programation.html.twig', [
            'id' => $id,
            'route' => $route,
            'programations' => json_decode($route->getProgramation()),
            'localities' => $entityManager->getRepository(Locality::class)->findAll(),
            'guides' => $entityManager->getRepository(User::class)->findByRoles(['ROLE_GUIDE'])
        ]);
    }

    public function configureMenuItems(): iterable
    {

        // Core configuration
        yield MenuItem::section('Funciones principales');
        yield MenuItem::linkToCrud('Rutas', 'fas fa-route', Entity\Route::class);
        yield MenuItem::linkToCrud('Planificación', 'fas fa-compass', Tour::class);
        yield MenuItem::linkToUrl('Calendario', 'fa-regular fa-calendar-days', $this->generateUrl('manage-tours'));
        yield MenuItem::linkToUrl('Gráfico', 'fa-solid fa-chart-line', $this->generateUrl('adminGraph-reservation'));

        // Entities
        yield MenuItem::section('Tours');
        yield MenuItem::linkToCrud('Reservas', 'fas fa-book-bookmark', Reservation::class);
        yield MenuItem::linkToCrud('Informes', 'fa-brands fa-wpforms', Report::class);
        yield MenuItem::linkToCrud('Valoraciones', 'fas fa-star-half-stroke', Ratings::class);

        // Locations
        yield MenuItem::section('Ubicaciones');
        yield MenuItem::linkToCrud('Visitas', 'fas fa-location-dot', Item::class);
        yield MenuItem::linkToCrud('Localidades', 'fas fa-tree-city', Locality::class);//fa-solid fa-map-pin
        yield MenuItem::linkToCrud('Provincias', 'fas fa-city', Province::class);//fa-regular fa-map
        
        // User
        yield MenuItem::section('Usuarios');
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-users', User::class);
        
        // Web Routes
        yield MenuItem::section('Rutas de la web');
        // yield MenuItem::linkToRoute('Inicio', 'fas fa-home', 'home'); // Forma mala, ya que en la url te redirige a través de /admin
        yield MenuItem::linkToUrl('Inicio', 'fas fa-home', $this->generateUrl('home'));
        yield MenuItem::linkToUrl('Inicio de sesión', 'fas fa-right-to-bracket', $this->generateUrl('action-login'));
        yield MenuItem::linkToUrl('Registro', 'fas fa-sign-hanging', $this->generateUrl('action-register'));

        // Testing
        // yield MenuItem::linkToRoute('Crear ruta', 'fas fa-signal', 'create-route');
        // Pruebas para formulario personalizado - START
        // yield MenuItem::linkToRoute('Rutas', 'fas fa-route', 'prueba');
        // yield MenuItem::linkToUrl('Rutas - linkToUrl(add Route)', 'fas fa-code-merge', $this->generateUrl('prueba'));
        // yield MenuItem::linkToRoute('create-route', 'fas fa-signal', 'create-route');
        // Pruebas para formulario personalizado - END

    
        // Testing 2
        // yield MenuItem::section('TESTING ROUTING');
        // yield MenuItem::linkToUrl('linkToUrl($this->generateUrl("home"))', 'fas fa-home', $this->generateUrl('home'));
        // yield MenuItem::linkToRoute('linkToRoute("home")', 'fas fa-signal', 'home');
        // yield MenuItem::linkToUrl('Mi página', 'fas fa-code-merge', $this->generateUrl('prueba2'));
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    // No se que le pasa
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        if (!$user instanceof User) {
            throw new \Exception('Usuario erroneo');
        }

        return parent::configureUserMenu($user)
            ->setAvatarUrl('uploads/images/'.$user->getPhoto())
            // ->setAvatarUrl('imgs-dir/'.$user->getPhoto())
            ->displayUserName(false)
            ->setName($user->getFormalName())
            // // Este código se utilizaría para al hacer click a un User puedas ir a otra pentalla que te salgan sus datos maquetados con my propio css&js
            // ->addMenuItems([
            //     MenuItem::linkToUrl('My Profile', 'fas fa-user', $this->generateUrl('show-user'))
            // ])
        ;
    }

    // configureAssets
    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css')
            // ->addJsFile('js/admin.js')
        ;
    }
}