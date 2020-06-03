<?php

namespace App\Controller;


use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Match;
use App\Entity\Participation;
use App\models\Category;
use App\Repository\MatchRepository;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    /**
     * Partie Résultats
     */

    /**
     * @Route("/match/{id}", name="detail_match", requirements={"id": "\d+"})
     */
    public function voirMatch($id, MatchRepository $mr){
        $match = $mr->find($id);

        return $this->render('event/match.html.twig', [
            "match" => $match
        ]);
    }

    /**
     * @Route("/planning/{idEvent}", name="voir_rencontres", requirements={"idEvent": "\d+"})
     */
    public function seeRencontresMeets($idEvent, ParticipationRepository $pr, EntityManagerInterface $em)
    {
        $event = $em->getRepository(Event::class)->find($idEvent);
        $nbrPoules = $pr->nbrPoules($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);

        //Tri les matchs par ordre chronologique
        $matchsTries = usort($matchs, function ($a, $b) {
            $ad = $a->getHeure();
            $bd = $b->getHeure();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad < $bd ? -1 : 1;
            }
        });
        return $this->render('event/planning.html.twig', [
            "matchs" => $matchs,
            "nbrPoules" => $nbrPoules,
            "event" => $event
        ]);
    }

    /**
     * @Route("/events/{idDiscipline}/{idCompetition}", name="events", requirements={"idDiscipline": "\d+", "idCompetition": "\d+"})
     * show all events of one discipline
     */
    public function getEvents($idDiscipline, $idCompetition, EntityManagerInterface $em)
    {
        $discipline = $em->getRepository(Discipline::class)->find($idDiscipline);
        $competition = $em->getRepository(Competition::class)->find($idCompetition);
        $events = $em->getRepository(Event::class)->findBy(["discipline" => $discipline, "competition" => $competition]);

        return $this->render('event/events.html.twig', [
            "events" => $events
        ]);
    }

    /**
     * @Route("/EnregistrerResultats/{idMatch}", name="enregistrer_resultats", requirements={"idMatch": "\d+"})
     */
    public function enregistrerResultats($idMatch, MatchRepository $mr, EntityManagerInterface $em, Request $request){
        $match = $mr->find($idMatch);
        $score1 = $request->request->get('score1');
        $score2 = $request->request->get('score2');

        $match->setScoreTeam1($score1);
        $match->setScoreTeam2($score2);
        $em->flush();
        $this->addFlash('success', 'Résultat modifié');
        return $this->redirectToRoute('event_voir_rencontres', ['idEvent' => $match->getEvent()->getId()]);
    }

    /**
     * Fonction qui crée le classement pour sports co
     * @Route("/creationClassement/{idEvent}", name="creation_classement", requirements={"idEvent": "\d+"})
     */
    public function classementSportsCo($idEvent, EntityManagerInterface $em){
        $participations = $em->getRepository(Participation::class)->findParticipationInAnEventSimple($idEvent);
        $event = $em->getRepository(Event::class)->find($idEvent);
        $matchs = $em->getRepository(Match::class)->findMatchesWithAnEvent($event);

        foreach ($participations as $participation){
            $participation->setVictory(0);
            $participation->setDefeat(0);
            $participation->setNul(0);
            $participation->setPointsClassement(0);
        }
        /**
         * @var $match Match
         */
        foreach ($matchs as $match){
            if($match->getScoreTeam1()){
                if($match->getScoreTeam1() > $match->getScoreTeam2()){
                    $match->getParticipation1()->setVictory($match->getParticipation1()->getVictory() +1);
                    $match->getParticipation1()->setPointsClassement( $match->getParticipation1()->getPointsClassement() +3);
                    $match->getParticipation2()->setDefeat($match->getParticipation2()->getDefeat() +1);
                }elseif ($match->getScoreTeam1() < $match->getScoreTeam2()){
                    $match->getParticipation2()->setVictory($match->getParticipation2()->getVictory() +1);
                    $match->getParticipation2()->setPointsClassement( $match->getParticipation2()->getPointsClassement() +3);
                    $match->getParticipation1()->setDefeat($match->getParticipation1()->getDefeat() +1);
                }else{
                    $match->getParticipation1()->setNul($match->getParticipation1()->getNul() +1);
                    $match->getParticipation1()->setPointsClassement( $match->getParticipation1()->getPointsClassement() +1);
                    $match->getParticipation2()->setNul($match->getParticipation2()->getNul() +1);
                    $match->getParticipation2()->setPointsClassement( $match->getParticipation2()->getPointsClassement() +1);
                }
            }else{

            }

        }
        $em->flush();
        return $this->redirectToRoute('event_afficher_classement', [
            "idEvent"=> $idEvent
        ]);
    }

    /**
     * @Route("afficherClassement/{idEvent}", name="afficher_classement", requirements={"idEvent": "\d+"})
     * fonction qui permet l'affichage du tableau
     */
    public function afficherClassement($idEvent, ParticipationRepository $pr){
        $participations = $pr->findParticipationInAnEventSimple($idEvent);

        //Etabli le classement par nbr de points
        $participationsTries = usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });
        return $this->render('event/classement.html.twig', [
            "participations" => $participations,
            "idEvent" => $idEvent
        ]);
    }

}
