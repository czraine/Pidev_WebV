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


class User2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Name fields
            ->add('userFirstname', TextType::class, [
                'label' => 'First Name'
            ])
            ->add('userLastname', TextType::class, [
                'label' => 'Last Name'
            ])
 
            // Contact fields
            ->add('userMail', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('userPhone', TelType::class, [
                'label' => 'Phone'
            ])
 
            // Login fields
            ->add('username', TextType::class, [
                'label' => 'Username'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password'
            ])
 
            // Role field
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Guide' => 'ROLE_Guide',
                    'Tourist' => 'ROLE_Tourist'
                ]
            ])
            ->add('nationality', CountryType::class, [
                'label' => 'Nationality'
            ])
 
            // Additional language field
            ->add('langue', LanguageType::class, [
                'label' => 'Additional Language'
            ])
            
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
