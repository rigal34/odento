<?php

namespace App\Controller\Admin;

use App\Entity\Comment; // Assure-toi que ce use est bien là aussi
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextareaField::new('content', 'Commentaire'),
            AssociationField::new('author', 'Auteur')->h
            AssociationField::new('post', 'Article de Blog'),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            BooleanField::new('isApproved', 'Approuvé ?'),
        ];
    }
    
}
