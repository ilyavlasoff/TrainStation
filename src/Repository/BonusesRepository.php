<?php

namespace App\Repository;

use App\Entity\Bonuses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bonuses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bonuses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bonuses[]    findAll()
 * @method Bonuses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonusesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bonuses::class);
    }

    // /**
    //  * @return Bonuses[] Returns an array of Bonuses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bonuses
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
