<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title :',
                'attr' => [
                    'class' => 'title'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'attr' => [
                    'class' => 'description',
                    'style' => 'resize:none;'
                ],
            ])
            ->add('plannedOn', DateTimeType::class, [
                'label' => 'Débute le :',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'title',
                    'style' => 'background-color:#fefefe00; color:#fff;'
                ]
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Fini le :',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'title',
                    'style' => 'background-color:#fefefe00; color:#fff;'
                ]
            ])
            ->add('image', TextType::class, [
                'label' => 'Images', 
                'attr' => [
                    'class' => 'title'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
