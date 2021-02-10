<?php

namespace App\Form;

use App\Entity\Message;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', TextType::class, $this->config('Nom'))
            ->add('email', EmailType::class, $this->config('Email'))
            ->add('phone', TelType::class, $this->config('Téléphone'))
            ->add('subject', ChoiceType::class, $this->config('Sujet', '', [
                'choices' => [
                    'Information produit' => 'Information produit',
                    'Information sur votre commande' => 'Information commande',
                    'Coaching' => 'Coaching',
                    'Autre' => 'Autre'
                ]
            ]))
            ->add('message', TextareaType::class, $this->config('Message'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
