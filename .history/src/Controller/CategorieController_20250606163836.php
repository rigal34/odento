<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

final class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
     public function categorie(CategoryRepository $categoryRepository, Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
   
    {
        $id = $request->query->get('id');
        if ($id) {
            $category = $categoryRepository->find($id);
            if ($category) {
                $articles = $category->getArticles();
            }
        }else {
            $articles = $articleRepository->findAll();
        }
        $parents = $categoryRepository->findBy(['parent' => null]);

    

        $articlesAll = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            4 /* limit per page */
        );

       

        return $this->render('home/categorie.html.twig', [
            'categories' => $parents,
            'articles' => $articlesAll,
        ]);
    }

    #[Route('/produit/{id}-{slug}', name: 'app_produit')]
     public function produit(Article $article): Response
   
    {

       

        return $this->render('home/detail_produit.html.twig', [
        
            'article' => $article,
        ]);
    }

}
