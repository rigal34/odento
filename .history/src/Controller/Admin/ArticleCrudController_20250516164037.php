<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Resource_;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
        
            TextField::new('title'),
            TextEditorField::new('content'),
            ImageField::new('image')->setBasePath('images/')->setUploadDir('public/images/')->setRequired(false),
            ImageField::new('images')
             ->setBasePath('images/')
             ->setUploadDir('public/images/')
             ->setRequired(false)
             ->setFormTypeOption('attr', ['multiple' => true]),
            NumberField::new('price')->setRequired(false),
            AssociationField::new('category')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Resource) return;

        // Récupération des fichiers uploadés
        $images = $_FILES['Resource']['name']['images'];

        if ($images) {
            $imagePaths = [];
            foreach ($images as $index => $imageName) {
                $tmpName = $_FILES['Resource']['tmp_name']['images'][$index];
                $newFilename = uniqid() . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
                move_uploaded_file($tmpName, 'public/images/uploads/' . $newFilename);
                $imagePaths[] = $newFilename;
            }
            $entityInstance->setImages($imagePaths);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
   
}
 