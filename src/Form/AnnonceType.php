<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, [
            'label' => false,
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'titre'],
            'row_attr' => ['class' => 'inputbox', 'id' => "user_email_formAcymailing54111"]
        ])
        ->add('content', TextareaType::class, [
            'label' => false,
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'annonce']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
