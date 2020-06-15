<?php

namespace App\Repository;

use App\Data\EventCriteria;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[] Returns an array of Product object
     */
    public function findEventsFiltered(EventCriteria $criteria)
    {

        $qb = $this->createQueryBuilder('e');
        if ($criteria->getSearch() != "") {
            $qb->andWhere($qb->expr()->like('e.name', ':name'))
                ->setParameter('name', "%" . $criteria->getSearch() . "%");
        }
        if ($criteria->getCompetition() != null) {
            $qb->andWhere("e.competition = :competition")
                ->setParameter('competition', $criteria->getCompetition());
        }
        if ($criteria->getDiscipline() != null) {
            $qb->andWhere("e.discipline = :discipline")
                ->setParameter('discipline', $criteria->getDiscipline());
        }
        if ($criteria->getGender() != "") {
            $qb->andWhere("e.gender = :gender")
                ->setParameter('gender', $criteria->getGender());
        }
        if ($criteria->getCategory() != "") {
            $qb->andWhere("e.category = :category")
                ->setParameter('category', $criteria->getCategory());
        }
        if ($criteria->getType() != null) {
            $qb->andWhere("e.type = :type")
                ->setParameter('type', $criteria->getType());
        }
        if ($criteria->getRound() != null) {
            $qb->andWhere("e.round = :round")
                ->setParameter('round', $criteria->getRound());
        }
        $qb->join("e.discipline", "d");
        $qb->join("e.category", 'ca');
        $qb->join("e.round", "r");
        $qb->join("e.type", "t");
        $qb->Select("e.id as eventId, e.published,e.name as eventName, d.name as disciplineName, r.name as roundName, ca.name as categoryName, t.name as typeName");
        return $qb->getQuery()->execute();
    }


    /**
     * Récupérer un event avec eventName, roundName et competition
     */
    public function findEventWithEventNameRoundNameAndCompetition($eventName, $roundName, $competition){
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.name = :eventName')
            ->setParameter('eventName', $eventName)
            ->andWhere('r.name = :roundName')
            ->setParameter('roundName', $roundName)
            ->andWhere('e.competition = :competition')
            ->setParameter('competition', $competition);
        $qb->leftJoin('e.round', 'r');
        return $qb->getQuery()->execute();
    }
    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
