<?php

namespace App\Repository;

use App\Entity\Match;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Match|null find($id, $lockMode = null, $lockVersion = null)
 * @method Match|null findOneBy(array $criteria, array $orderBy = null)
 * @method Match[]    findAll()
 * @method Match[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Match::class);
    }

    /**
     * find matches for an event
     */
    public function findMatchesWithAnEvent($event){
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('m.event = :event')
            ->setParameter('event', $event);

        return $qb->getQuery()->execute();
    }

    /**
     * find matches for an event and round
     */
    public function findMatchesWithAnEventAndRound($eventName, $round, $competition){
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('e.name = :event')
            ->setParameter('event', $eventName)
            ->andWhere('e.competition = :competition')
            ->setParameter('competition', $competition)
            ->andWhere('e.round = :round')
            ->setParameter('round', $round);
        $qb->leftJoin('m.event', 'e');
        return $qb->getQuery()->execute();
    }

    /**
     * find matches for an event and poule
     */
    public function findMatchesWithAnEventAndPoule($event, $poule){
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('m.event = :event')
            ->setParameter('event', $event)
            ->andWhere('p1.poule = :poule')
            ->setParameter('poule', $poule);
        $qb->leftJoin('m.participation1', 'p1');
        return $qb->getQuery()->execute();
    }

    /**
     * Fonction qui permet de récuperer une discipline avec l'id d'un match
     */
    public function getDisicplineWithMatch($idMatch){
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere("m.id = :idMatch")
            ->setParameter("idMatch", $idMatch);
        $qb->leftJoin("m.event", 'e');
        $qb->leftJoin("e.discipline", "d");
        $qb->select("m.id, d.name, d.sets");
        return $qb->getQuery()->execute();
    }

    /**
     * fonction qui permet de retourner un match avec 2 participations et un evenement
     */
    public function findMatchWithAnEventand2Participations($event, $part1, $part2){
        $qb = $this->createQueryBuilder('m');
        $qb->andWhere('m.event = :event')
            ->setParameter('event', $event)
            ->andWhere('m.participation1 = :part1')
            ->setParameter("part1", $part1)
            ->andWhere('m.participation2 = :part2')
            ->setParameter('part2', $part2);
        return $qb->getQuery()->execute();
    }
    // /**
    //  * @return Match[] Returns an array of Match objects
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
    public function findOneBySomeField($value): ?Match
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
