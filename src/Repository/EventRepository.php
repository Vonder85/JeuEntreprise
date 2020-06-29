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
        $qb->join('e.competition', 'co');
        $qb->Select("e.id as eventId, e.published,e.name as eventName, co.id as competitionId, d.name as disciplineName, d.id as disciplineId, r.name as roundName, ca.name as categoryName, t.name as typeName");
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

    /**
     * Fonction qui récupère les medailles pour un pays
     */
    public function recuperermedaillesPays($competition){
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.competition = :competition')
            ->setParameter('competition', $competition);
        $qb->leftJoin('e.participation', "pa");
        $qb->leftJoin('e.type', 't');
        $qb->leftJoin('pa.participant', 'p');
        $qb->leftJoin('p.athlet', 'a');
        $qb->leftJoin('p.team', 'team');
        $qb->leftJoin('team.company', 'c');
        $qb->select('COALESCE((a.country), c.country) as country, COUNT(pa.goldMedal) as goldMedal, COUNT(pa.silverMedal) as silverMedal, COUNT(pa.bronzeMedal) as bronzeMedal');
        $qb->orderBy('COUNT(pa.goldMedal)', 'desc');
        $qb->addOrderBy('COUNT(pa.silverMedal)', 'desc');
        $qb->addOrderBy('COUNT(pa.bronzeMedal)', 'desc');
        $qb->groupBy('country' );
        return $qb->getQuery()->execute();
    }

    /**
     * Fonction qui récupère les medailles pour entreprises
     */
    public function recuperermedaillesEntreprises($competition){
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere('e.competition = :competition')
            ->setParameter('competition', $competition);
        $qb->leftJoin('e.participation', "pa");
        $qb->leftJoin('pa.participant', 'p');
        $qb->leftJoin('p.athlet', 'a');
        $qb->leftJoin('a.company', 'c');
        $qb->leftJoin('p.team', 'team');
        $qb->leftJoin('team.company', 'tc');
        $qb->select('COALESCE((c.name), tc.name) as name, COUNT(pa.goldMedal) as goldMedal, COUNT(pa.silverMedal) as silverMedal, COUNT(pa.bronzeMedal) as bronzeMedal');
        $qb->orderBy('COUNT(pa.goldMedal)', 'desc');
        $qb->addOrderBy('COUNT(pa.silverMedal)', 'desc');
        $qb->addOrderBy('COUNT(pa.bronzeMedal)', 'desc');
        $qb->groupBy('name');
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
