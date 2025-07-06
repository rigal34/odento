<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder; 
/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

 public function findArticlesQueryBuilder(int $categoryId, string $searchTerm, string $priceOrder): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c');

        if ($categoryId > 0) {
            $qb->andWhere('c.id = :catId')
                ->setParameter('catId', $categoryId);
        }
        // Gestion de la recherche par titre
        if ($searchTerm !== '') {
            $qb->andWhere('a.title LIKE :term')
                ->setParameter('term', '%'.$searchTerm.'%');
        }
             // Gestion du tri par prix
        if (in_array($priceOrder, ['asc', 'desc'], true)) {
            $qb->orderBy('a.price', strtoupper($priceOrder));
        } else {
            $qb->orderBy('a.createdAt', 'DESC');
        }
        
        return $qb;
    }
}


    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

