<?php
// src/Form/ReportType.php

namespace App\Form;

use App\Entity\Tour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tours = $options['tours'];

        $builder
            ->add('tour_id', ChoiceType::class, [
                'label' => 'Select Tour',
                // 'choices' => $this->formatTourChoices($tours),
                'choices' => $tours,
            ])
            ->add('observation', TextareaType::class, [
                'label' => 'Observation'
            ])
            ->add('money', MoneyType::class, [
                'label' => 'Money'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Report',
            'tours' => [], // Default value for tours
        ]);
    }

    // private function formatTourChoices($tours)
    // {
    //     $choices = [];
    //     foreach ($tours as $tour) {
    //         $choices[$tour->getId()] = $tour->getDatetime();
    //     }
    //     return $choices;
    // }
}