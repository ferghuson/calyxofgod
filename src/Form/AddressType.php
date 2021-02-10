<?php

namespace App\Form;

use App\Entity\Address;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias', TextType::class, $this->config('Alias', 'Ex: maison'))
            ->add('firstName', TextType::class, $this->config('Prénom'))
            ->add('lastName', TextType::class, $this->config('Nom'))
            ->add('country', TextType::class, $this->config('Pays', '',['data'=>'Togo', 'disabled'=>true]))
            ->add('city', TextType::class, $this->config('Ville', '',['data'=>'Lomé', 'disabled'=>true]))
            ->add('district', TextType::class, $this->config('Quartier'))
            ->add('details', TextareaType::class, $this->config('Détails'))
            ->add('phone', TelType::class, $this->config('Téléphone'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
