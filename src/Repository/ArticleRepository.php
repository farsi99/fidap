<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * cette méthode traite l'affichage de l'ensemble d'article d'un type predefinie
     * @param string $_type
     * @return Response
     */
    public function getArticles($_type)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('t.libelle =:val')
            ->setParameter('val', $_type)
            ->join('a.typeArticle', 't')
            ->getQuery()
            ->getResult();
    }

    /**
     * Cette requette traite l'affichage des menus sur la page d'accueil
     *
     * @return Response
     */
    public function getMenu()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id,a.titre,a.menu, a.slugMenu,a.MenuParent,a.ordre,a.publication')
            ->andWhere('a.MenuParent is Null')
            ->andWhere('a.AffichageMenu =:val')
            ->andWhere('t.libelle=:val1')
            ->setParameter('val', 1)
            ->setParameter('val1', 'page')
            ->join('a.typeArticle', 't')
            ->orderBy('a.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getSousMenu()
    {
        return $this->createQueryBuilder('a')
            ->select('a.id,a.titre,a.menu, a.slugMenu,a.MenuParent,a.ordre,a.publication')
            ->andWhere('a.MenuParent is not Null')
            ->andWhere('a.AffichageMenu =:val')
            ->andWhere('t.libelle=:val1')
            ->setParameter('val', 1)
            ->setParameter('val1', 'page')
            ->join('a.typeArticle', 't')
            ->orderBy('a.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * cette méthode permet de retoutner un article de type page
     *
     * @param [type] $page
     * @return void
     */
    public function findByOnPage($page)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.slugMenu = :val')
            ->andWhere('t.libelle = :val1')
            ->setParameter('val', $page)
            ->setParameter('val1', 'page')
            ->join('a.typeArticle', 't')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
