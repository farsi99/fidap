<?php

namespace App\Repository;

use App\Entity\Inviter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Inviter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inviter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inviter[]    findAll()
 * @method Inviter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InviterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inviter::class);
    }

    // /**
    //  * @return Inviter[] Returns an array of Inviter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inviter
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
