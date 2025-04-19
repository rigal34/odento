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
    public function categorie(CategoryRepository $categoryRepository): Response
    {
        
        $parents = 

        

        return $this->render('home/categorie.html.twig', [
            'vetement' => $categoryVetement ? $categoryVetement->getName() : 'Catégorie par défaut',
            
           
        ]);
    }
}
