<?php

namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function getTicketList(User $user, int $quantity = null, int $offset = null) {
        $qb = $this->createQueryBuilder('ti');
        $qb
            ->select('ti.id, v.name, v.departmentDate, tr.route, tr.trainType, ti.place')
            ->join('App\Entity\Voyage', 'v', Join::WITH,'ti.voyage = v.id')
            ->leftJoin('App\Entity\Wagon', 'w', Join::WITH, 'ti.wagon = w.id')
            ->join('App\Entity\Train', 'tr', Join::WITH, 'v.train = tr.id')
            ->where('ti.user = :user')
            ->setParameter('user', $user);
        if ($quantity && $offset) {
            $qb
                ->setFirstResult($offset)
                ->setMaxResults($quantity);
        }
        return $qb->getQuery()->execute();
    }
}
