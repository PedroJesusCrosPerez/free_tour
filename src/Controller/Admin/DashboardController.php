<?php
namespace App\Controller\Admin;

use App\Entity;
use App\Entity\Item;
use App\Entity\Locality;
use App\Entity\Province;
use App\Entity\Ratings;
use App\Entity\Report;
use App\Entity\Reservation;
use App\Entity\Tour;
use App\Entity\User;
// use App\Entity\Route;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
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

    public function configureMenuItems(): iterable
    {
        // User
        yield MenuItem::section('Usuarios');
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-users', User::class);

        // Route
        // yield MenuItem::section('Rutas');
        // yield MenuItem::linkToCrud('Ruta','fas fa-solid fa-code-merge', Entity\Route::class);

        // Locality
        yield MenuItem::section('Ubicaciones');
        yield MenuItem::linkToCrud('Visitas', 'fas fa-signal', Item::class);
        yield MenuItem::linkToUrl('Rutas - linkToUrl(add Route)', 'fas fa-code-merge', $this->generateUrl('home'));
        yield MenuItem::linkToCrud('Rutas - linkToCrud(CRUD Route)', 'fas fa-signal', Entity\Route::class);
        yield MenuItem::linkToRoute('Rutas', 'fas fa-signal', 'home');
        yield MenuItem::linkToCrud('Localidades', 'fas fa-solid fa-map-pin', Locality::class);
        yield MenuItem::linkToCrud('Provincias', 'fas fa-regular fa-map', Province::class);

        // Entities
        yield MenuItem::section('Tours');
        yield MenuItem::linkToCrud('Tour', 'fas fa-signal', Tour::class);
        yield MenuItem::linkToCrud('Reservas', 'fas fa-signal', Reservation::class);
        yield MenuItem::linkToCrud('Informes', 'fas fa-signal', Report::class);
        yield MenuItem::linkToCrud('Valoraciones', 'fas fa-signal', Ratings::class);
        
        // Web Routes
        yield MenuItem::section('Rutas de la web');
        // yield MenuItem::linkToRoute('Inicio', 'fas fa-home', 'home'); // Forma mala, ya que en la url te redirige a través de /admin
        yield MenuItem::linkToUrl('Inicio', 'fas fa-home', $this->generateUrl('home'));
        yield MenuItem::linkToUrl('Inicio de sesión', 'fas fa-right-to-bracket', $this->generateUrl('action-login'));
        yield MenuItem::linkToUrl('Registro', 'fas fa-sign-hanging', $this->generateUrl('action-register'));
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
}