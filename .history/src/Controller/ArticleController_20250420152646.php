<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function article(ArticleRepository $articleRepository,): Response
    {
        $parents = $categoryRepository->findBy(['parent' => null]);

        return $this->render('home/categorie.html.twig', [
            
            'articles' => $articles,
        ]);
    }
    
        
    }

