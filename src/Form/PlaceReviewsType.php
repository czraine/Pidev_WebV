<?php

namespace App\Form;

use App\Entity\PlaceReviews;
use App\Entity\Placetovisit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use App\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class PlaceReviewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('reviewId', EntityType::class, [
        //     'class' => PlaceReviews::class,
        //     'choice_label' => 'reviewId',
        //     'disabled' => true,
        //     'attr' => [
        //         'readonly' => true,
        //     ],
        // ])        
            ->add('placeName')
            ->add('rating')
            ->add('reviewTxt')
            // ->add('reviewDate')
            ->add('reviewDate', DateTimePickerType::class, [
                'label' => 'reviewDate',
                'disabled' => false, // This option disables the field and sets the "disabled" HTML attribute
                'attr' => [
                    'readonly' => true, // This attribute makes the field read-only
                ],  
            ])
            // ->add('idUser')
            ->add('idUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'idUser',
                'disabled' => true,
                'attr' => [
                    'readonly' => true,
                ],
            ]) 
            ->add('place')
            ->add('place', EntityType::class, [
                'class' => Placetovisit::class,
                'choice_label' => 'Place_Name',
            ])
            ->getForm();
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlaceReviews::class,
        ]);
    }
}
