<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;


final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
public function categorie(CategoryRepository $categoryRepository): Response
{
    $categories = $categoryRepository->findAll();

    return $this->render('home/categorie.html.twig', [
        'categories' => $categories,
    ]);
}
}
