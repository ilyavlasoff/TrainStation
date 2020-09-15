<?php

namespace App\Repository;

use App\Entity\Train;
use App\Entity\Voyage;
use App\Entity\Wagon;
use App\Entity\WagonType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wagon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wagon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wagon[]    findAll()
 * @method Wagon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WagonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wagon::class);
    }

    public function getWagonsByType(Train $train, WagonType $wagonType): array {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('wgn.id')
            ->from('App\Entity\Train', 'tr')
            ->join('App\Entity\Wagon', 'wgn', Join::WITH, 'tr.id=wgn.train')
            ->join('App\Entity\WagonType', 'wt', Join::WITH, 'wgn.type=wt.id')
            ->where('wt.id = :wagonType')
            ->andWhere('tr.id = :train')
            ->setParameter('wagonType', $wagonType->getId())
            ->setParameter('train', $train->getId())
            ->getQuery()->getResult();
    }


}
