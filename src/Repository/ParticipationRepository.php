<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    /**
     * get participation for an event
     */
    public function findParticipationInAnEvent($idEvent){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.event = :event")
            ->setParameter("event", $idEvent)
        ->groupBy("pa.id");
        $qb->join('p.participant', 'pa');
        $qb->leftJoin('pa.athlet', 'a');
        $qb->leftJoin('pa.team', 't');
        $qb->leftJoin('a.company', 'ac');
        $qb->leftJoin('t.teamCreated', 'tcr');
        $qb->leftJoin('tcr.athlet', 'atcr');
        $qb->leftJoin('atcr.company', 'atcrc');
        $qb->select('p.id, pa.id as participantId, p.poule as participationPoule, pa.name as participantName, a.name as athletName, a.firstname as athletFirstname, ac.name as athletCompany, ac.country as athletCountry, t.name as teamName, atcrc.name as companyName, atcrc.country as countryName');
        return $qb->getQuery()->execute();
    }

    /**
     * get PArticipation avec un participant
     */
    public function getParticipationAvecUnParticipant($idEvent, $idParticipant){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.event = :event")
            ->setParameter("event", $idEvent)
            ->andWhere("p.participant = :participant")
            ->setParameter("participant", $idParticipant);
        return $qb->getQuery()->execute();
    }

    /**
     * get participations for an event simple
     */
    public function findParticipationInAnEventSimple($idEvent){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.event = :event")
            ->setParameter("event", $idEvent)
            ->groupBy("pa.id");
        $qb->leftJoin('p.participant', 'pa');
        return $qb->getQuery()->execute();
    }

    /**
     * delete participation in an event
     */
    public function deleteParticipationEvent($idEvent, $idParticipant){
        $qb = $this->createQueryBuilder('p');
        $qb->delete('App:Participation', 'p')
            ->andWhere("p.event = :event")
            ->setParameter("event", $idEvent)
            ->andWhere("p.participant = :participant")
            ->setParameter("participant", $idParticipant);
        return $qb->getQuery()->execute();

    }

    /**
     * Récupération nombre de poules
     */
    public function nbrPoules($idEvent){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.event = :event")
            ->setParameter("event", $idEvent)
            ->groupBy("p.poule");
        return $qb->getQuery()->execute();
    }

    /**
     * Récupération par poules
     */
    public function getParPoules($idEvent, $poule){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.event = :event")
            ->setParameter("event", $idEvent)
            ->andWhere("p.poule = :poule")
            ->setParameter("poule", $poule);
        return $qb->getQuery()->execute();
    }

    /**
     * Recupère les participation avec un nom d'event
     */
    public function getWithEventName($eventName){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("e.name = :eventName")
            ->setParameter("eventName", $eventName)
            ->orderBy("p.positionClassement", 'ASC');
        $qb->leftJoin('p.event', 'e');
        $qb->leftJoin('p.participant', 'pa');
        $qb->leftJoin();
        return $qb->getQuery()->execute();
    }

    /**
     * get participation for an event
     */
    public function getParticipationWithAnEventName($eventName, $competition){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("e.name = :event")
            ->setParameter("event", $eventName)
            ->andWhere("e.competition = :competition")
            ->setParameter("competition", $competition)
            ->groupBy('p.positionClassement')
            ->orderBy("p.positionClassement", 'ASC');;
        $qb->leftJoin('p.participant', 'pa');
        $qb->leftJoin('p.event', 'e');
        $qb->leftJoin('pa.athlet', 'a');
        $qb->leftJoin('pa.team', 't');
        $qb->leftJoin('a.company', 'ac');
        $qb->leftJoin('t.teamCreated', 'tcr');
        $qb->leftJoin('tcr.athlet', 'atcr');
        $qb->leftJoin('atcr.company', 'atcrc');
        $qb->select('p.id, p.positionClassement, pa.id as participantId, p.poule as participationPoule, pa.name as participantName, a.name as athletName, a.firstname as athletFirstname, ac.name as athletCompany, ac.country as athletCountry, t.name as teamName, atcrc.name as companyName, atcrc.country as countryName');
        return $qb->getQuery()->execute();
    }

    /**
     * Recupère nbr participants avec un nom d'event
     */
    public function getNbrParticipantsWithEventName($eventName){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("e.name = :eventName")
            ->setParameter("eventName", $eventName)
            ->groupBy('pa.id');
        $qb->leftJoin('p.event', 'e');
        $qb->leftJoin('p.participant', 'pa');
        return $qb->getQuery()->execute();
    }

    /**
     * Recupère nbr participants avec un event et une poule
     */
    public function getNbrParticipantsWithEventAndPoule($event, $poule){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere("p.event = :event")
            ->setParameter("event", $event)
            ->andWhere("p.poule = :poule")
            ->setParameter('poule', $poule);
        return $qb->getQuery()->execute();
    }


    /**
     * find participations for an event and round
     */
    public function findParticipationsWithAnEventAndRound($eventName, $round, $competition){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('e.name = :event')
            ->setParameter('event', $eventName)
            ->andWhere('e.competition = :competition')
            ->setParameter('competition', $competition)
            ->andWhere('e.round = :round')
            ->setParameter('round', $round);
        $qb->leftJoin('p.event', 'e');
        return $qb->getQuery()->execute();
    }
    // /**
    //  * @return Participation[] Returns an array of Participation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
