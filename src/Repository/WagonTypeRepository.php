<?php

namespace App\Repository;

use App\Entity\WagonType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WagonType|null find($id, $lockMode = null, $lockVersion = null)
 * @method WagonType|null findOneBy(array $criteria, array $orderBy = null)
 * @method WagonType[]    findAll()
 * @method WagonType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WagonTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WagonType::class);
    }

    // /**
    //  * @return WagonType[] Returns an array of WagonType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WagonType
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
