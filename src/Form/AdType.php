<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Category;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Titre',
                'attr' => [
                    'placeholder'=> 'Veuillez saisir un titre'
                ],
            ])
            ->add('category',EntityType::class,[
                'class'=> Category::class,
                'choice_label'=> 'libelle',
            ])
            ->add('description',TextareaType::class,[
                'label'=> 'Description',
                'attr' => [
                    'placeholder'=> 'Veuillez saisir une description'
                ],
            ])
            ->add('city',TextType::class,[
                'label'=> 'Ville',
                'attr' => [
                    'placeholder'=> 'Veuillez saisir une ville'
                ],
            ])
            ->add('zip',IntegerType::class,[
                'label'=>'Code postal',
                'attr' => [
                    'placeholder'=> 'Veuillez saisir le code postal'
                ],
            ])
            ->add('price', IntegerType::class,[
                'label'=> 'Prix',
                'attr' => [
                    'placeholder'=> 'Veuillez saisir un prix'
                ]
            ])
            ->add('characteristics', CollectionType::class,[
                'entry_type'=> CharacteristicType::class,
                'entry_options' => array('label' => false),
                'allow_add'=> true,
                'prototype'=> true,
                // permet de pouvoir créer une caractéristique à partir d'annonce
                'by_reference'=>false

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
