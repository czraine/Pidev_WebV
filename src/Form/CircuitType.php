<?php

namespace App\Form;

use App\Entity\Circuit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CircuitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vdep', ChoiceType::class, [
                'choices' => $options['villes'],
                'choice_label' => 'nomVille',
                'choice_value' => 'nomVille',
                'label' => 'Ville dep ',
            ])
            ->add('varr', ChoiceType::class, [
                'choices' => $options['villes'],
                'choice_label' => 'nomVille',
                'choice_value' => 'nomVille',
                'label' => 'Ville arr ',
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('prix')
            ->add('duree')
            ->add('nom')
            ->add('description')
            ->add('imagesrc', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Image'],
            ]
            
        )            ->add("nbplace")        ;

        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Circuit::class,
            'villes' => []
        ]);
    }
}
