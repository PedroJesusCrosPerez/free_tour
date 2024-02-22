<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\RouteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    // private $tours;

    // public function __construct(array $tours)
    // {
    //     $this->tours = $tours;
    // }

    // private $routeRepository;
    // public function __construct(RouteRepository $routeRepository) {
    //     $this->routeRepository = $routeRepository;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        dump($options);
        dd($this->tours);
        
        $builder
        ->add('number_tickets', IntegerType::class, [
            'label' => 'Número de personas',
        ])
        // ->add('tour', ChoiceType::class, [
        //     'class' => Tour::class,
        //     'choice_label' => function ($tours) {
        //         return $tours->__toString();
        //     },
        //     'label' => 'Tour',
        //     'placeholder' => 'Selecciona un tour',
        // ])
        ->add('tour', EntityType::class, [
            'class' => Tour::class,
            'choices' => $this->tours,
            'choice_label' => 'nombre', // Nombre de la propiedad de Tour que quieres mostrar en el select
            'placeholder' => 'Selecciona un tour', // Texto de placeholder opcional
            // Otras opciones de configuración si las necesitas
        ]);
        ;
    }

}
