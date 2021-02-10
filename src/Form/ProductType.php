<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->config('Nom', 'Nom du produit'))
            ->add('price', IntegerType::class, $this->config('Prix', 'Prix du produit', ['attr' => ['min' => 0]]))
            ->add('selected', ChoiceType::class, $this->config('Mise en avant', '', [
                'choices' => [
                    'Non' => false,
                    'Oui' => true
                ]
            ]))
            ->add('image', FileType::class, $this->config('Image', '', [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une image valide (.jpg ou .jpeg)'
                    ])
                ]
            ]))
            ->add('delivery', TextType::class, $this->config('Durée de livraison', 'Ex: 1-3 heures'))
            ->add('description', TextareaType::class, $this->config('Descriptif', 'Description du produit'))
            ->add('tags', TextareaType::class, $this->config('Mots clés', 'Pour retrouver facilement ce produit'))
            ->add('features', TextareaType::class, $this->config('Caractéristiques', 'Caractéristiques du produit', ['required'=>false]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
