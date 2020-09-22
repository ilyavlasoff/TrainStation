<?php

namespace App\Repository;

use App\Entity\Voyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voyage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voyage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voyage[]    findAll()
 * @method Voyage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyage::class);
    }

    public function getAvailableVoyages(string $from, string $to, \DateTime $date) {

        $availableVoyages = $this->getEntityManager()->createQueryBuilder()
            ->select('v.id as voyageId, v.name, v.departmentDate, tr.route, tr.trainType')
            ->from('App\Entity\Voyage', 'v')
            ->join('App\Entity\Train', 'tr', Join::WITH, 'tr.id = v.train')
            ->where($this->getEntityManager()->createQueryBuilder()->expr()->in('tr',
                $this->getEntityManager()->createQueryBuilder()
                    ->select('tr2')
                    ->from('App\Entity\Train', 'tr2')
                    ->join('App\Entity\WayThrough', 'wt2', Join::WITH, 'wt2.train = tr2.id')
                    ->join('App\Entity\Station', 'st2', Join::WITH, 'wt2.station = st2.id')
                    ->where('st2.id = :to')
                    ->andWhere($this->getEntityManager()->createQueryBuilder()->expr()->exists(
                        $this->getEntityManager()->createQueryBuilder()
                            ->select('tr3')
                            ->from('App\Entity\Train','tr3')
                            ->join('App\Entity\WayThrough', 'wt3', Join::WITH, 'wt3.train = tr3.id')
                            ->join('App\Entity\Station', 'st3', Join::WITH, 'wt3.station = st3.id')
                            ->where('st3.id = :from')
                            ->andWhere('tr3 = tr2')
                            ->getDQL()
                    ))
                    ->getDQL()
            ))
            ->andWhere($this->getEntityManager()->createQueryBuilder()->expr()->between('v.departmentDate', ':timeStart', ':timeEnd'))
            ->setParameter('timeStart', $date->format('Y-m-d 00:00'))
            ->setParameter('timeEnd', $date->format('Y-m-d 23:59'))
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->getQuery()->getResult();

            return $availableVoyages;
    }

    public function getVoyagesList(\DateTime $dateTime) {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('v')
            ->from('App\Entity\Voyage', 'v')
            ->where($this->getEntityManager()->createQueryBuilder()->expr()->between('v.departmentDate', ':timeStart', ':timeEnd'))
            ->orderBy('v.departmentDate', 'ASC')
            ->setParameter('timeStart', $dateTime->format('Y-m-d 00:00'))
            ->setParameter('timeEnd', $dateTime->format('Y-m-d 23:59'))
            ->getQuery()->getResult();
    }

}
