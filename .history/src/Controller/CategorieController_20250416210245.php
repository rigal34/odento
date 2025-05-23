<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function categorie(EntityManagerInterface $entityManager): Response
    {
        $category = null; // Define $category or fetch it from a data source
        return $this->render('home/categorie.html.twig', [
            'voiture' => $category ? $category->getName() : 'Catégorie par défaut',

        ]);
    }
}
