<?php

namespace App\Utils;
use App\Entity\Event;

class EventUtils
{
    public static function setResult($match, $score1, $score2)
    {
        $match->setScoreTeam1($score1);
        $match->setScoreTeam2($score2);
        $match->getParticipation1()->setPointsMarques($match->getParticipation1()->getPointsMarques()+$score1);
        $match->getParticipation1()->setPointsEncaisses($match->getParticipation1()->getPointsEncaisses()+$score2);
        $match->getParticipation2()->setPointsMarques($match->getParticipation2()->getPointsMarques()+$score2);
        $match->getParticipation2()->setPointsEncaisses($match->getParticipation1()->getPointsEncaisses()+$score1);

        $match->setWinner(self::getWinner($match, $score1, $score2));
        $match->setLooser(self::getLooser($match, $score1, $score2));

    }

    public static function getWinner($match, $score1, $score2)
    {
        if ($score1 > $score2) {
            return $match->getParticipation1();
        } elseif ($score1 < $score2) {
            return $match->getParticipation2();
        }
    }

    public static function getLooser($match, $score1, $score2)
    {
        if ($score1 > $score2) {
            return $match->getParticipation2();
        } elseif ($score1 < $score2) {
            return $match->getParticipation1();
        }
    }

    public static function genererResultatsMatchsAleatoire($matchs){
        foreach ($matchs as $match){
            self::setResult($match, rand(0,30), rand(0,30));
        }
    }

    public static function classerParPoints($participations){
        usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                if($a->getVictory() > $b->getVictory()){
                    return -1;
                }elseif($a->getVictory() < $b->getVictory()){
                    return 1;
                }else{
                    if($a->getNul() > $b->getNul()){
                        return -1;
                    }elseif($a->getNul() > $b->getNul()){
                        return 1;
                    }else{
                        if(($a->getPointsMarques() - $a->getPointsEncaisses()) > ($b->getPointsMarques() - $b->getPointsEncaisses())){
                            return -1;
                        }elseif (($a->getPointsMarques() - $a->getPointsEncaisses()) < ($b->getPointsMarques() - $b->getPointsEncaisses())){
                            return 1;
                        }else{
                            return 0;
                        }
                    }
                }
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });
        return $participations;
    }

    public static function classerParPointsPoules($participations, $poules)
    {
        for ($j = 0; $j < sizeof($poules); $j++) {
            usort($participations[$j], function ($a, $b) {
                $ad = $a->getPointsClassement();
                $bd = $b->getPointsClassement();
                if ($ad == $bd) {
                    if ($a->getVictory() > $b->getVictory()) {
                        return -1;
                    } elseif ($a->getVictory() < $b->getVictory()) {
                        return 1;
                    } else {
                        if ($a->getNul() > $b->getNul()) {
                            return -1;
                        } elseif ($a->getNul() > $b->getNul()) {
                            return 1;
                        } else {
                            if (($a->getPointsMarques() - $a->getPointsEncaisses()) > ($b->getPointsMarques() - $b->getPointsEncaisses())) {
                                return -1;
                            } elseif (($a->getPointsMarques() - $a->getPointsEncaisses()) < ($b->getPointsMarques() - $b->getPointsEncaisses())) {
                                return 1;
                            } else {
                                return 0;
                            }
                        }
                    }
                } else {
                    return $ad > $bd ? -1 : 1;
                }
            });
        }
        return $participations;
    }

    public static function creationPhase($event, $round){
        $event1 = new Event();
        $event1->setDiscipline($event->getDiscipline());
        $event1->setGender($event->getGender());
        $event1->setMeridianBreak($event->getMeridianBreak());
        $event1->setDuration($event->getDuration());
        $event1->setBreakRest($event->getBreakRest());
        $event1->setCategory($event->getCategory());
        $event1->setType($event->getType());
        $event1->setCompetition($event->getCompetition());
        $event1->setName($event->getName());
        $event1->setNbrFields($event->getNbrFields());
        $event1->setStartAt($event->getStartAt()->add(new \DateInterval('P1DT0H')));
        $event1->setMeridianBreakHour($event->getMeridianBreakHour()->add(new \DateInterval('P1DT0H')));
        $event1->setRound($round);
        $event1->setPhase($event->getPhase());

        return $event1;
    }
}