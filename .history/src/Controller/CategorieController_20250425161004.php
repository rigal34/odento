<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
     public function categorie(CategoryRepository $categoryRepository, Request $request): Response
   
    {
        $id = $request->query->get('id');
        
        $parents = $categoryRepository->findBy(['parent' => null]);

        return $this->render('home/categorie.html.twig', [
            'categories' => $parents,
        ]);
    }

}
