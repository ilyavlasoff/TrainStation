<?php

namespace App\Repository;

use App\Entity\WayThrough;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WayThrough|null find($id, $lockMode = null, $lockVersion = null)
 * @method WayThrough|null findOneBy(array $criteria, array $orderBy = null)
 * @method WayThrough[]    findAll()
 * @method WayThrough[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WayThroughRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WayThrough::class);
    }

    // /**
    //  * @return WayThrough[] Returns an array of WayThrough objects
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
    public function findOneBySomeField($value): ?WayThrough
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
