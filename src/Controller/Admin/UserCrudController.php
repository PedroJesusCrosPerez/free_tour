<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
// use EasyCorp\Bundle\EasyAdminBundle\Config\;
// use EasyCorp\Bundle\EasyAdminBundle\Config\;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Actions, Action};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCrudController extends AbstractCrudController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ->disable(Action::NEW, Action::DELETE);
            ->add(Crud::PAGE_EDIT, Action::INDEX)

            ->add(Crud::PAGE_NEW, Action::new('myCustomAction', 'Mi Acción Personalizada')
                ->linkToCrudAction('myCustomCrudAction')
                ->setIcon('fa fa-custom-icon')
                ->addCssClass('btn btn-custom')
            )
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ["ROLE_ADMIN", "ROLE_GUIDE", "ROLE_CLIENT"];

        $passwordField = TextField::new('password', 'Password')
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
            ]);

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Nombre')->hideOnIndex(),
            TextField::new('surname')->setLabel('Apellidos')->hideOnIndex(),
            TextField::new('fullName')->setLabel('Nombre completo')->hideOnForm(),
            EmailField::new('email'),
            $passwordField,
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
            BooleanField::new('is_verified')->setLabel('Verificado')->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashPassword($user): void
    {
        if ($user instanceof User && $plainPassword = $user->getPassword()) {
            $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }
    }
}
