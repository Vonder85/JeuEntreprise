<?php

namespace App\Repository;

use App\Entity\EventWithParticipation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventWithParticipation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventWithParticipation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventWithParticipation[]    findAll()
 * @method EventWithParticipation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventWithParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventWithParticipation::class);
    }

    // /**
    //  * @return EventWithParticipation[] Returns an array of EventWithParticipation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventWithParticipation
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
