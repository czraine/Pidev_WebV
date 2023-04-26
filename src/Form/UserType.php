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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;



class UserType extends AbstractType
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }
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
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'multiple' => true,
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Guide' => 'ROLE_Guide',
                    'Tourist' => 'ROLE_Tourist'
                ],
                'data' => $options['data']->getRoles(), // Set the default value
                'expanded' => $this->authorizationChecker->isGranted('ROLE_ADMIN'), // Expand only for admin users
                'disabled' => !$this->authorizationChecker->isGranted('ROLE_ADMIN'), // Disable for non-admin users
            ])
            
            ->add('lang1', LanguageType::class, [
                'label' => 'Primary Language'
            ])
            ->add('lang2', LanguageType::class, [
                'label' => 'Secondary Language'
            ])
            ->add('lang3', LanguageType::class, [
                'label' => 'Tertiary Language'
            ])
 
            // Location fields
            ->add('cityname', TextType::class, [
                'label' => 'City'
            ])
            ->add('nationality', CountryType::class, [
                'label' => 'Nationality'
            ])
 
            // Additional language field
            ->add('langue', LanguageType::class, [
                'label' => 'Additional Language'
            ])
            ->add('Register',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
