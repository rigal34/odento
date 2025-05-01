<?php

namespace App\Controller;

use App\Entity\Category; 
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, \Doctrine\ORM\EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findOneBy(['name' => 'default']);
        $articles = $articleRepository->findBy(['category' => $category]);
        return $this->render('home/categorie.html.twig', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
    
        'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,'category' => $category,
    }

