<?php

namespace App\Repository;

use App\Entity\Monitoring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Monitoring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monitoring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monitoring[]    findAll()
 * @method Monitoring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonitoringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monitoring::class);
    }

    // /**
    //  * @return Monitoring[] Returns an array of Monitoring objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Monitoring
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
