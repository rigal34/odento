<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    
    
    public function categorie(EntityManagerInterface $entityManager): Response
    
    {
    

        $categoryVetement = $entityManager->getRepository(Category::class)->findOneBy([14]);
        $categoryElectronique = $entityManager->getRepository(Category::class)->findOneBy([]);
        

        return $this->render('home/categorie.html.twig', [
            'vetement' => $categoryVetement ? $categoryVetement->getName() : 'Catégorie par défaut',
           'electronique' => $categoryElectronique ? $categoryElectronique->getName() : 'Catégorie par défaut',
           
        ]);
    }
}
