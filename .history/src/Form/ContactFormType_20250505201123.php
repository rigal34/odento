<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Votre Nom', 
            'required' => true,     // Champ obligatoire
            'constraints' => [      // Règles de validation
                new NotBlank([      // Ne doit pas être vide
                    'message' => 'Veuillez entrer votre nom.',
                ]),
            ],
            'attr' => [             // Attributs HTML pour le champ <input>
                'placeholder' => 'Entrez votre nom' 
            ]
        ])
        ->add('email', EmailType::class, [ // Type spécifique pour email
            'label' => 'Votre Email',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre adresse email.',
                ]),
                new Email([         // Doit être un format email valide
                    'message' => "L'adresse email '{{ value }}' n'est pas valide.",
                ]),
            ],
            'attr' => [
                'placeholder' => 'nom@exemple.com'
            ]
        ])
        ->add('sujet', TextType::class, [
            'label' => 'Sujet',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un sujet.',
                ]),
            ],
             'attr' => [
                'placeholder' => 'Sujet de votre message'
            ]
        ])
        ->add('message', TextareaType::class, [ // Type pour une zone de texte plus grande
            'label' => 'Votre Message',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer votre message.',
                ]),
            ],
             'attr' => [
                'rows' => 6, // Hauteur indicative de la zone de texte
                'placeholder' => 'Écrivez votre message ici...'
            ]
        ])
    ;
    // Pas besoin de bouton 'submit' ici, on le mettra dans Twig
    // --- FIN DES CHAMPS A AJOUTER ---
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
