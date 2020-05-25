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

    /**
     * get athlet where id team
     */
    public function athletinTeam($idTeam){
        $qb = $this->createQueryBuilder('t');
        $qb->andWhere("t.team = :team")
            ->setParameter("team", $idTeam);
        $qb->join('t.athlet', 'a');
        $qb->leftJoin('t.team', 'team');
        $qb->select('team.id, team.name, a.id, a.name, a.firstname, a.dateBirth, a.reference');
        return $qb->getQuery()->execute();
    }

    /**
     * delete athlet in team
     */
    public function deleteAthletinTeam($idAthlet, $idTeam){
        $qb = $this->createQueryBuilder('t');
        $qb->delete('App:TeamCreated', 't')
        ->andWhere("t.athlet = :athlet")
            ->setParameter("athlet", $idAthlet)
        ->andWhere("t.team = :team")
            ->setParameter("team", $idTeam);
        return $qb->getQuery()->execute();

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
