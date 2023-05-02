<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Name fields
            ->add('userFirstname', TextType::class, [
                'label' => 'First Name','attr'=>['class'=>'form-control']
            ])
            ->add('userLastname', TextType::class, [
                'label' => 'Last Name','attr'=>['class'=>'form-control']
            ])
 
            // Contact fields
            ->add('userMail', EmailType::class, [
                'label' => 'Email','attr'=>['class'=>'form-control']
            ])
            ->add('userPhone', TelType::class, [
                'label' => 'Phone','attr'=>['class'=>'form-control']
            ])
 
            // Login fields
            ->add('username', TextType::class, [
                'label' => 'Username','attr'=>['class'=>'form-control']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password','attr'=>['class'=>'form-control']
            ])
 
            // Role field
            ->add('role', ChoiceType::class, [
                'label' => 'Role','attr'=>['class'=>'form-control'],
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Guide' => 'ROLE_Guide',
                    'Tourist' => 'ROLE_Tourist'
                ]
            ])
 
            
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
