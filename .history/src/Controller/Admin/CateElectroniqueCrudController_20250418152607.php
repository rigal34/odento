<?php

namespace App\Controller\Admin;

use App\Entity\CateElectronique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CateElectroniqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CateElectronique::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // C'est souvent mieux de cacher l'ID dans les formulaires
            // Utilisez le nom CORRECT de la propriété: 'electronique'
            TextField::new('electronique', 'Catégorie Electronique'),
            // Si vous voulez ajouter une description plus tard, il faudra d'abord
            // ajouter la propriété $description à l'entité CateElectronique
            // TextEditorField::new('description'),
        ];
    }
    
}
