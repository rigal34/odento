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
    
    
    //public function categorie(EntityManagerInterface $entityManager): Response
    public function categorie(CategoryRepository $categoryRepository): Response
    {
        $categoryZone1 = $categoryRepository->findOneBy(['code' => 'section1']);

       // $categoryVetement = $entityManager->getRepository(Category::class)->findOneBy([]);

        

        return $this->render('home/categorie.html.twig', [
            //'vetement' => $categoryVetement ? $categoryVetement->getName() : 'Catégorie par défaut',
            'categoryZone1' => $categoryZone1
           
        ]);
    }
}
