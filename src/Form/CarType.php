<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Engine;
use App\Entity\Seller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class)
            ->add('model', TextType::class)
            ->add('bodyType', ChoiceType::class, [
                'choices' => [
                    'Convertible' => 'Convertible', 
                    'Hatchback' => 'Hatchback', 
                    'Minivan' => 'Minivan', 
                    'Sedan' => 'Sedan', 
                    'SUV' => 'SUV', 
                ]
            ])
            ->add('registrationExpirationDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class)
            ->add('image', TextType::class)
            ->add('url', TextType::class)
            ->add('comment', TextareaType::class)
            ->add('price', NumberType::class, [
                'html5' => true,
            ])
            ->add('seller', SellerType::class)
            ->add('engine', EngineType::class)
            ->add('submit', SubmitType::class, ['label' => 'Add new car'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
