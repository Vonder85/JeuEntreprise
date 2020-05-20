<?php

namespace App\Repository;

use App\Entity\TeamCreated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamCreated|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamCreated|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamCreated[]    findAll()
 * @method TeamCreated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamCreatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamCreated::class);
    }

    // /**
    //  * @return TeamCreated[] Returns an array of TeamCreated objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamCreated
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
