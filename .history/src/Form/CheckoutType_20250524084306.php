<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom de famille',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre nom de famille'
                ]
            ])
       
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse e-mail'
                ]
            ])
            
            ->add('phone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone'
                ]
            
                ])
            ->add('address', TextType::class, [
                'label' => 'Adresse (rue, numéro)', 
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex: 123 rue de la Paix'
                ]
            ])
            ->add('apartment', TextType::class, [
                'label' => 'Appartement étage, etc. (optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Appartement 7B, Bâtiment A'
                ]
            ])
            -> add('city', TextType::class, [
                'label' => 'Ville',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])
            -> add('state', TextType::class, [
                'label' => 'État / Région (Optionnel)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre état ou région'
                ]
            ])
            -> add('zipCode', TextType::class, [
                'label' => 'Code postal',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre code postal'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'required' => true,
                'preferred_choices' => ['FR', 'AN', 'DE', 'IT', 'ES']
                'attr' => [
                    'placeholder' => 'Sélectionnez votre pays',
                ]
            ])
   
   
   
   
   
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
