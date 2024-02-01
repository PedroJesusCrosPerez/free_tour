<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $roles = ["ROLE_ADMIN", "ROLE_GUIDE", "ROLE_CLIENT"];//, "ROLE_USER"];
        return [
            IdField::new('id')
                // ->onlyOnIndex() // Hacen casi lo mismo
                ->hideOnForm(),
            TextField::new('name')
                ->setLabel('Nombre')
                ->hideOnIndex(),
            TextField::new('surname')
                ->setLabel('Apellidos')
                ->hideOnIndex(),
            TextField::new('fullName')
                ->setLabel('Nombre completo')
                ->hideOnForm(),
            EmailField::new('email'),
            // PasswordField::new('plainPassword')
            //     ->onlyOnForms()
            //     ->setRequired(false),
            TextField::new('password', 'Password')
                ->setLabel('Contraseña')
                ->onlyWhenCreating()
                ->setFormType(PasswordType::class)
                ->setFormTypeOption('constraints', [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ]),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices(),
            ImageField::new('photo')
                ->setLabel('Foto de perfil')
                ->setBasePath('uploads/images/')
                ->setUploadDir('public/uploads/images/')
                ->setUploadedFileNamePattern('[uuid].[extension]')
                ->setRequired(false)
                ->setHelp('Sube una imagen de perfil'),
            BooleanField::new('is_verified')
                ->setLabel('Verificado')
                ->hideOnForm(),
            
            // $userPasswordHasher->hashPassword(
            //     $user,
            //     $form->get('plainPassword')->getData()
            // ),
        ];
    }
}
