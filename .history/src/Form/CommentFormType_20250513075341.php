<?php

namespace App\Form;


use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Laissez votre commentaire ici...'
                ]
            ])
          
            ->add('submit', SubmitType::class, [
                'label' => 'Publier le commentaire',
                'attr' => [
                    'class' => 'btn btn-primary mt-3' // Classe Bootstrap pour le style, tu peux l'adapter
                ]
            ])
            // 👆👆👆 FIN DES LIGNES À AJOUTER 👆👆👆
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
