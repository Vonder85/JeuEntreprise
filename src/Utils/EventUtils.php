<?php

namespace App\Utils;
use App\Entity\Event;
use App\Entity\Match;
use App\Entity\Round;

class EventUtils
{
    public static function setResult($match, $score1, $score2)
    {
        $match->setScoreTeam1($score1);
        $match->setScoreTeam2($score2);

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

    public static function equipePresente($phase, $rencontre)
    {
        $part1 = $rencontre->getParticipation1();
        $part2 = $rencontre->getParticipation2();
        if (sizeof($phase) > 0) {
            if ($part1 === $phase[0]->getParticipation1() || $part1 === $phase[0]->getParticipation2() || $part2 === $phase[0]->getParticipation1() || $part2 === $phase[0]->getParticipation2()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function classerParPoints($participations){
        $participationsTries = usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                return 0;
            } else {
                return $ad > $bd ? -1 : 1;
            }
        });
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