<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\Locality;
use Doctrine\ORM\EntityManager;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextEditorField::new('description'),
            ImageField::new('photo')
                ->setBasePath('images/item/')
                ->setUploadDir('public/images/item/')
                ->setUploadedFileNamePattern('[uuid].[extension]'),
            TextField::new('coordinates'),
            AssociationField::new('locality')
                ->setFormTypeOptions([
                    'placeholder' => 'Selecciona una localidad',
                ]),
            // IntegerField::new('locality_id'),
        ];
    }
}
