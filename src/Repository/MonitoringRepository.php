<?php

namespace App\Repository;

use App\Entity\Monitoring;
use App\Entity\Train;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function getMonitoringHistory($train = null, \DateTime $dateTime = null, int $count = 10) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('m')
            ->from('App\Entity\Train', 'tr')
            ->join('App\Entity\Monitoring', 'm', Join::WITH, 'tr.id = m.train')
            ->orderBy('m.time', 'DESC');
        if ($train) {
            $qb->andWhere('tr.id = :train')->setParameter('train', $train->getId());
        }
        if($dateTime) {
            $qb->andWhere($this->getEntityManager()->createQueryBuilder()->expr()->between('m.time', ':timeStart', ':timeEnd'))
                ->setParameter('timeStart', $dateTime->format('Y-m-d 00:00'))
                ->setParameter('timeEnd', $dateTime->format('Y-m-d 23:59'));
        }
        if ($count) {
            $qb->setMaxResults($count);
        }

        return $qb->getQuery()->getResult();
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
