<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        $userCrudController = new UserCrudController();
        $userCrudController->configureFields('CRUD User');
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ["ROLE_ADMIN", "ROLE_USER", "ROLE_GUIDE"];
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nombre'),
            TextField::new('email'),
            TextField::new('password'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices(),
            ImageField::new('foto')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/images/')
                ->setUploadedFileNamePattern('[uuid].[extension]'),
        ];
    }
}
