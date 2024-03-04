<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\Route;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RouteCrudController extends AbstractCrudController
{
    private $uploadsDir;
    public function __construct(ParameterBagInterface $parameterBag) { $this->uploadsDir = $parameterBag->get('routeImgDir'); }
    public static function getEntityFqcn(): string { return Route::class; }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            // // imports the given entrypoint defined in the importmap.php file of AssetMapper
            // // it's equivalent to adding this inside the <head> element:
            // // {{ importmap('admin') }}
            // ->addAssetMapperEntry('admin')
            // // you can also import multiple entries
            // // it's equivalent to calling {{ importmap(['app', 'admin']) }}
            // ->addAssetMapperEntry('app', 'admin')

            // // adds the CSS and JS assets associated to the given Webpack Encore entry
            // // it's equivalent to adding these inside the <head> element:
            // // {{ encore_entry_link_tags('...') }} and {{ encore_entry_script_tags('...') }}
            // ->addWebpackEncoreEntry('admin-app')

            // // it's equivalent to adding this inside the <head> element:
            // // <link rel="stylesheet" href="{{ asset('...') }}">
            // ->addCssFile('build/admin.css')
            // ->addCssFile('https://example.org/css/admin2.css')

            // // it's equivalent to adding this inside the <head> element:
            // // <script src="{{ asset('...') }}"></script>
            // ->addJsFile('build/admin.js')
            // ->addJsFile('https://example.org/js/admin2.js')

            // // use these generic methods to add any code before </head> or </body>
            // // the contents are included "as is" in the rendered page (without escaping them)
            // ->addHtmlContentToHead('<link rel="dns-prefetch" href="https://assets.example.com">')
            // ->addHtmlContentToBody('<script> ... </script>')
            ->addHtmlContentToBody('<p>Esto es un texto generado por MI</p> <!-- generated at '.time().' -->')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setLabel("Nombre"),
            // ImageField::new('photo')
            //     ->setLabel("Foto")
            //     ->setBasePath('images/route/')
            //     ->setUploadDir('public/images/route/')
            //     ->setUploadedFileNamePattern('[randomhash].[extension]'),
            ImageField::new('photo')
                ->setLabel('Foto')
                // ->setBasePath('images/route/')
                // ->setUploadDir('public/images/route/')
                // ->setUploadedFileNamePattern('[uuid].[extension]')
                ->setRequired(false)
                ->setHelp('Sube una imagen de ruta'),
            IntegerField::new('capacity')
                ->setLabel("Capacidad"),
            DateField::new('datetime_start')
                ->setLabel("Fecha de inicio"),
            DateField::new('datetime_end')
                ->setLabel("Fecha de fin"),
        ];
    }


    public function editRedirect(AdminContext $context){
        $entityInstance = $context->getEntity()->getInstance();
        $id= $entityInstance->getId();
        // dd($id);
        return $this->redirectToRoute('edit-route',['id' => $id]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->linkToRoute('create-route', []) // Redirijir Action::NEW a formulario personalizado en plantilla twig
                    ->setIcon('fa-solid fa-circle-plus') // Icono personalizado
                    ->setLabel("Crear ruta") // Label personalizado
                ;
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT,function(Action $action){
                return $action
                    ->linkToCrudAction('editRedirect') //Redirijir Action::EDIT a formulario personalizado en plantilla twig
                    ->setIcon('fa fa-file-alt') // Icono personalizado 
                    ->setLabel("Editar") // Label personalizado
                ;
            })
            // ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            //     return $action
            //         // Redirijir Action::EDIT a formulario personalizado en plantilla twig
            //         ->linkToRoute('create-route', ["id"=>"hola"]) // TODO pasar datos para rellenar campos
            //         ->setIcon('fa fa-file-alt') // Icono personalizado 
            //         ->setLabel("Editar") // Label personalizado
            //     ;
            // })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    // ->setIcon('fa-regular fa-trash-can') // Sin relleno
                    ->setIcon('fa-solid fa-trash') // Icono personalizado // Con relleno
                    ->setLabel("Eliminar") // Label personalizado
                ;
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action
                    ->setIcon('fas fa-search content-search-icon') // Icono personalizado  // Con relleno
                    ->setLabel("Detallar")// Label personalizado
                ;
            })
        ;
    }

}
