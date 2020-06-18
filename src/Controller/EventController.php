<?php

namespace App\Controller;


use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Event;
use App\Entity\Match;
use App\Entity\Participation;
use App\Entity\Round;
use App\Repository\EventRepository;
use App\Repository\MatchRepository;
use App\Repository\ParticipationRepository;
use App\Utils\EventUtils;
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

        //Enregistre les résultats
        EventUtils::setResult($match, $score1, $score2);

        switch ($match->getEvent()->getRound()->getName()){
            case "Finale": $match->getWinner()->setPositionClassement(1);
                            $match->getLooser()->setPositionClassement(2);
                            break;
            case "3ème place": $match->getWinner()->setPositionClassement(3);
                                $match->getLooser()->setPositionClassement(4);
                                break;
            case "5ème place": $match->getWinner()->setPositionClassement(5);
                                $match->getLooser()->setPositionClassement(6);
                                break;
            case "7ème place": $match->getWinner()->setPositionClassement(7);
                                $match->getLooser()->setPositionClassement(8);
                                break;
            case "9ème place": $match->getWinner()->setPositionClassement(9);
                                $match->getLooser()->setPositionClassement(10);
                                break;
            case "11ème place": $match->getWinner()->setPositionClassement(11);
                                $match->getLooser()->setPositionClassement(12);
                                break;
            case "13ème place": $match->getWinner()->setPositionClassement(13);
                                $match->getLooser()->setPositionClassement(14);
                                break;
            case "15ème place": $match->getWinner()->setPositionClassement(15);
                                $match->getLooser()->setPositionClassement(16);
                                break;
            case "17ème place": $match->getWinner()->setPositionClassement(17);
                                $match->getLooser()->setPositionClassement(18);
                                break;
            case "19ème place": $match->getWinner()->setPositionClassement(19);
                                $match->getLooser()->setPositionClassement(20);
                                break;
        }
        $em->flush();
        $this->addFlash('success', 'Résultat modifié');
        if($this->isGranted("ROLE_ADMIN")){
            return $this->redirectToRoute('admin_see_planning_meets', ["idEvent" => $match->getEvent()->getId()]);
        }
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
        $roundPoules = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsTotal = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundPoules, $event->getCompetition());

        if($participations[0]->getPoule() === null){
            $this->addFlash("info", "Les poules ne sont pas encore créées, veuillez les créer.");
            return $this->redirectToRoute('admin_edit_event',['id'=>$idEvent]);
        }

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
            if($match->getScoreTeam1() === 0 || $match->getScoreTeam1() > 0){
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
            }

        }
        if($event->getRound()->getName() === "Poule de classement"){
            $j= 0;
            if(sizeof($participationsTotal) === 5){
                $j=3;
            }elseif(sizeof($participationsTotal) === 7) {
                $j = 5;
            }elseif(sizeof($participationsTotal) === 9){
                $j=7;
            }elseif (sizeof($participationsTotal) === 11){
                $j=9;
            }elseif (sizeof($participationsTotal) === 15){
                $j=13;
            }elseif (sizeof($participationsTotal) === 19){
                $j=17;
            }

            $participations = EventUtils::classerParPoints($participations);
            foreach ($participations as $participation){
                $participation->setPositionClassement($j);
                $j++;
            }
        }

        if($event->getPhase() === 3){
            $j=0;
            if(sizeof($participationsTotal) == 7){
                if($event->getRound()->getName() == "Tournoi consolante"){
                    $j= 5;
                    $participations = EventUtils::classerParPoints($participations);
                    foreach ($participations as $participation){
                        $participation->setPositionClassement($j);
                        $j++;
                    }
                }
            }elseif(sizeof($participationsTotal) == 9){
                if($event->getRound()->getName() == "Tournoi consolante"){
                    $j= 7;
                    $participations = EventUtils::classerParPoints($participations);
                    foreach ($participations as $participation){
                        $participation->setPositionClassement($j);
                        $j++;
                    }
                }
            }elseif(sizeof($participationsTotal) == 13){
                if($event->getRound()->getName() == "Tournoi consolante"){
                    $j= 9;
                    $participations = EventUtils::classerParPoints($participations);
                    foreach ($participations as $participation){
                        $participation->setPositionClassement($j);
                        $j++;
                    }
                }
            }
        }

        $em->flush();
        return $this->redirectToRoute('event_afficher_classement', [
            "idEvent"=> $idEvent,
        ]);
    }

    /**
     * @Route("/afficherClassement/{idEvent}", name="afficher_classement", requirements={"idEvent": "\d+"})
     * fonction qui permet l'affichage du tableau
     */
    public function afficherClassement($idEvent, ParticipationRepository $pr, EventRepository $er, EntityManagerInterface $em){
        $participations = $pr->findParticipationInAnEventSimple($idEvent);
        $event = $er->find($idEvent);
        $roundPoules = $em->getRepository(Round::class)->findOneBy(['name' => 'Phase de poules 1']);
        $participationsTotal = $em->getRepository(Participation::class)->findParticipationsWithAnEventAndRound($event->getName(), $roundPoules, $event->getCompetition());

        $participationsPoule = [];
        $poules =[];

        if($event->getPoule()){
            $poules = $pr->nbrPoules($idEvent);
            for ($i = 0; $i < sizeof($poules); $i++) {
                $participationsPoule[] = $pr->getParPoules($idEvent, $poules[$i]->getPoule());
            }
            $participationsPoule = EventUtils::classerParPointsPoules($participationsPoule, $poules);

        }else{
            //Etabli le classement par nbr de points
           $participations = EventUtils::classerParPoints($participations);
        }
        return $this->render('event/classement.html.twig', [
            "participations" => $participations,
            "event"=>$event,
            "participationsPoule" => $participationsPoule,
            "poules" => $poules,
            "participationsDebut" => $participationsTotal
        ]);
    }

    /**
     * fonction qui étbalit le classement génral
     * @Route("/classementGeneral/{idEvent}", name="classement_general", requirements={"idEvent": "\d+"})
     */
    public function classementGeneral($idEvent, EntityManagerInterface $em){
        $event = $em->getRepository(Event::class)->find($idEvent);
        $events = $em->getRepository(Event::class)->findBy(['name' => $event->getName()]);
        $participations = $em->getRepository(Participation::class)->getParticipationWithAnEventName($event->getName(), $event->getCompetition());
        dump($participations);

        $classement = [];
        foreach ($events as $item){
            $item->setPublished(true);
        }
        foreach ($participations as $participation){
            if($participation['positionClassement'] !== null){
                $classement[] = $participation;
            }
        }
        $em->flush();

            return $this->render('event/classementGeneral.html.twig',[
            "event" => $event,
            "classement" => $classement
        ]);
    }
}
