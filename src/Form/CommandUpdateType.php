<?php

namespace App\Form;

use App\Entity\Command;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shipping', IntegerType::class, $this->config('Livraison', 'Frais de port', ['attr' => ['min' => 0]]))
            ->add('state', ChoiceType::class, $this->config('Etat de la commande', '', [
                'choices' => [
                    'En attente de paiement' => 'En attente de paiement',
                    'En cours de préparation' => 'En cours de préparation',
                    'Livré' => 'Livré',
                    'Annulé' => 'Annulé'
                ]
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
