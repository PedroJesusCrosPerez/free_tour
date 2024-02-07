<?php

namespace App\Controller\Admin;

use App\Entity\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RouteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Route::class;
    }

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

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnDetail(),
            TextField::new('name'),
            TextEditorField::new('description')
                ->setFormTypeOptions([
                    'attr' => ['maxlength' => 1000]
                ]),
            ImageField::new('photo')
                ->setBasePath('images/route/')
                ->setUploadDir('public/images/route/')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            TextField::new('coordinates'),
            IntegerField::new('capacity'),
            DateField::new('datetime_start'),
            DateField::new('datetime_end'),
        ];
    }
    */

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->linkToRoute('create-route', []);
            })
            // ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            //     return $action->linkToRoute('ruta', ["id"=>"hola"]);
            // })
            ;
    }

}
