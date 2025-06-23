<?php

namespace App\Controller;

// ... (tous les 'use' restent les mêmes)
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategorieController extends AbstractController
{
   #[Route('/categorie', name: 'app_categorie')]
    public function categorie(
        CategoryRepository $categoryRepository,
        ArticleRepository  $articleRepository,
        Request            $request,
        PaginatorInterface $paginator
    ): Response {
        // 1. Récupération des filtres GET
        $categoryId = $request->query->getInt('id', 0);
        $searchTerm = trim($request->query->get('productSearch', ''));
        $priceOrder = $request->query->get('priceRange', ''); // '', 'asc' ou 'desc'
        $page       = $request->query->getInt('page', 1);

        // 2. Construction du QueryBuilder
        //$qb = $articleRepository->createQueryBuilder('a')
         $qb = $articleRepository->findArticlesQueryBuilder($categoryId, $searchTerm, $priceOrder);
         $articlesPagination = $paginator->paginate($qb, $page, 2);
         $parents = $categoryRepository->findBy(['parent' => null]);
            //->leftJoin('a.category', 'c')
            //->addSelect('c');

        if ($categoryId > 0) {
            $qb->andWhere('c.id = :catId')
               ->setParameter('catId', $categoryId);
        }

        if ($searchTerm !== '') {
            $qb->andWhere('a.title LIKE :term')
               ->setParameter('term', '%'.$searchTerm.'%');
        }

        // 3. Application du tri : prix ou date
        if (in_array($priceOrder, ['asc', 'desc'], true)) {
            $qb->orderBy('a.price', strtoupper($priceOrder));
        } else {
            $qb->orderBy('a.createdAt', 'DESC');
        }

        // 4. Pagination
        $articlesPagination = $paginator->paginate(
            $qb,
            $page,
            2 // nombre d'articles par page
        );

        // 5. Récupération des catégories parentes pour le menu
        $parents = $categoryRepository->findBy(['parent' => null]);

        // 6. Rendu de la vue
        return $this->render('home/categorie.html.twig', [
            'categories' => $parents,
            'articles'   => $articlesPagination,
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
