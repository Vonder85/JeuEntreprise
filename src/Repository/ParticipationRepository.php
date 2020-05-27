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
        $qb->select('p.id, pa.id as participantId, pa.name as participantName, a.name as athletName, a.firstname as athletFirstname, ac.name as athletCompany, ac.country as athletCountry, t.name as teamName, atcrc.name as companyName');
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
