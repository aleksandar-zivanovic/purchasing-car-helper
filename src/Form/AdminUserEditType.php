<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminUserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Client' => 'ROLE_USER',
                    'Salesperson' => 'ROLE_SALESPERSON',
                    'Admin' => 'ROLE_ADMIN',
                ],
                // setting to display current role in dropdown menu
                'data' => [0 => $options['data']->getRoles()[0]], 
            ])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Verify user',
            ])
            ->add('submit', SubmitType::class)
        ;
        
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray): string {
                    return implode(', ', $rolesAsArray);
                },
                function ($rolesAsString): array {
                    return explode(', ', $rolesAsString);
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
