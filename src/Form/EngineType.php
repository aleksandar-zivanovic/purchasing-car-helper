<?php

namespace App\Form;

use App\Entity\Engine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EngineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fuelType', ChoiceType::class, [
                'choices' => [
                    'Petrol' => 'Petrol', 
                    'Petrol LPG' => 'Petrol LPG', 
                    'Diesel' => 'Diesel', 
                    'Electric' => 'Electric'
                ]
            ])
            ->add('engineDisplacement', NumberType::class)
            ->add('powerKW', NumberType::class)
            ->add('powerHP', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Engine::class,
        ]);
    }
}
