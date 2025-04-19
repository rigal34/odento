<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function categorie(EntityManagerInterface $entityManager): Response
    {

        $category = $entityManager->getRepository(Category::class)->findOneBy([]);
        return $this->render('home/categorie.html.twig', [
            'voiture' => $category ? $category->getName() : 'Catégorie par défaut',
            'electronique' => $category ? $category->getDescription() : 'Description par défaut',

        ]);
    }
}
