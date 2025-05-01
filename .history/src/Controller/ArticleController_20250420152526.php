<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository,): Response
    {
        $articles = $articleRepository->findAll();
        return $this->render('home/categorie.html.twig', [
            
            'articles' => $articles,
        ]);
    }
    
        
    }

