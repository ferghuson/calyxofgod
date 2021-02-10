<?php

namespace App\Form;

use App\Entity\Payment;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('method', ChoiceType::class, $this->config('Veuillez sélectionner un mode de paiement', '', [
                'choices' => [
                    'Virement bancaire [UTB : 0101804247320050047]' => 'Virement bancaire',
                    'T-money [90 10 39 29]' => 'T-money',
                    'Flooz [96 22 48 12]' => 'Flooz'
                ],
                'expanded'=>true
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
