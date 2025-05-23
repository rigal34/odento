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

        $categoryVoiture = $entityManager->getRepository(Category::class)->findOneBy([]);
        $categoryComposants = $entityManager->getRepository(Category::class)->findOneBy(['name' => 'camion']);

        return $this->render('home/categorie.html.twig', [
            'voiture' => $categoryVoiture ? $categoryVoiture->getName() : 'Catégorie par défaut',
            'electronique' => $categoryComposants ? $categoryComposants->getName() : 'Description par défaut',
        ]);
    }
}
