<?php

namespace App\Controller\Admin;

use App\Entity\Locality;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LocalityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Locality::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            IntegerField::new('province_id'),
            AssociationField::new('province')
                ->setFormTypeOptions([
                    'placeholder' => 'Selecciona una provincia',
                ]),
        ];
    }
}
