<?php

namespace App\Repository;

use App\Entity\Train;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Train|null find($id, $lockMode = null, $lockVersion = null)
 * @method Train|null findOneBy(array $criteria, array $orderBy = null)
 * @method Train[]    findAll()
 * @method Train[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Train::class);
    }

    public function getAvailableWagonTypes(Train $train) : array {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('wt')
            ->from('App\Entity\Train', 'tr')
            ->join('App\Entity\Wagon', 'wgn', Join::WITH, 'tr.id = wgn.train')
            ->join('App\Entity\WagonType', 'wt', Join::WITH, 'wgn.type = wt.id')
            ->where('tr.id = :train')
            ->setParameter('train', $train->getId())
            ->getQuery()->getResult();
    }

    public function getAvailableStations(Train $train): array {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('st')
            ->from('App\Entity\Train', 'tr')
            ->join('App\Entity\WayThrough', 'wt', Join::WITH, 'wt.train = tr.id')
            ->join('App\Entity\Station', 'st', Join::WITH, 'wt.station = st.id')
            ->where('tr.id = :train')
            ->setParameter('train', $train->getId())
            ->getQuery()->getResult();
    }
}
