<?php

namespace App\Form;

use App\Entity\Customer;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', ChoiceType::class, $this->config('Civilité', '', [
                'attr' => ['class'=>'form-check-inline'],
                'choices' => ['Madame'=>'Mme', 'Monsieur'=>'M.'],
                'expanded'=>true
            ]))
            ->add('firstName', TextType::class, $this->config('Prénom'))
            ->add('lastName', TextType::class, $this->config('Nom'))
            ->add('email', EmailType::class, $this->config('Email'))
            ->add('phone', TelType::class, $this->config('Téléphone'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
