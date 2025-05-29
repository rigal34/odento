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
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(private SluggerInterface $slugger)
    {
    }


    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
        
            TextField::new('title'),
            TextEditorField::new('content'),
            ImageField::new('image')->setBasePath('images')->setUploadDir('public/images')->setRequired(false),
            ImageField::new('images')->setBasePath('images')->setUploadDir('public/images')->setRequired(false)
            ->setFormTypeOptions(),
            NumberField::new('price')->setRequired(false),
            AssociationField::new('category')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Article) return;

        $entityInstance->setSlug($this->slugger->slug($entityInstance->getTitle())->lower());

        parent::persistEntity($entityManager, $entityInstance);
    }

   
   
}


