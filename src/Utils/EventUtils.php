<?php

namespace App\Utils;

use App\Entity\Event;
use App\Repository\MatchRepository;
use Doctrine\ORM\EntityManagerInterface;

class EventUtils
{

    /**
     * @var MatchRepository
     */
    private $mr;

    public function __construct(MatchRepository $mr)
    {
        $this->mr = $mr;
    }

    public static function setResult($match, $score1, $score2)
    {
        $match->setScoreTeam1($score1);
        $match->setScoreTeam2($score2);
        $match->getParticipation1()->setPointsMarques($match->getParticipation1()->getPointsMarques() + $score1);
        $match->getParticipation1()->setPointsEncaisses($match->getParticipation1()->getPointsEncaisses() + $score2);
        $match->getParticipation2()->setPointsMarques($match->getParticipation2()->getPointsMarques() + $score2);
        $match->getParticipation2()->setPointsEncaisses($match->getParticipation1()->getPointsEncaisses() + $score1);

        $match->setWinner(self::getWinner($match, $score1, $score2));
        $match->setLooser(self::getLooser($match, $score1, $score2));
    }

    public static function setResultSportsSets($match, $score1, $score2, $detail)
    {
        $match->setScoreTeam1($score1);
        $match->setScoreTeam2($score2);
        $match->setDetail($detail);


        $match->getParticipation1()->setSetsMarques($match->getParticipation1()->getSetsMarques() + $score1);
        $match->getParticipation2()->setSetsEncaisses($match->getParticipation2()->getSetsEncaisses() + $score2);
        $match->getParticipation2()->setSetsMarques($match->getParticipation2()->getSetsMarques() + $score2);
        $match->getParticipation1()->setSetsEncaisses($match->getParticipation1()->getSetsEncaisses() + $score1);

        $scoreDetail = self::recupererDetailEquipeSportsSets($detail);

        $pointsEquipe1 = array_sum($scoreDetail[0]);
        $pointsEquipe2 = array_sum($scoreDetail[1]);

        $match->getParticipation1()->setPointsMarques($match->getParticipation1()->getPointsMarques() + $pointsEquipe1);
        $match->getParticipation1()->setPointsEncaisses($match->getParticipation1()->getPointsEncaisses() + $pointsEquipe2);
        $match->getParticipation2()->setPointsMarques($match->getParticipation2()->getPointsMarques() + $pointsEquipe2);
        $match->getParticipation2()->setPointsEncaisses($match->getParticipation1()->getPointsEncaisses() + $pointsEquipe1);

        $match->setWinner(self::getWinner($match, $score1, $score2));
        $match->setLooser(self::getLooser($match, $score1, $score2));
    }

    public static function setResultRencontre($rencontre, $score1, $score2, $detail)
    {
        $rencontre->setScoreTeam1($score1);
        $rencontre->setScoreTeam2($score2);
        $rencontre->setDetail($detail);

        $rencontre->getMatch()->setSetsTeam1($rencontre->getMatch()->getSetsTeam1() + $score1);
        $rencontre->getMatch()->setSetsTeam2($rencontre->getMatch()->getSetsTeam2() + $score2);
        $rencontre->getMatch()->getParticipation1()->setSetsMarques($rencontre->getMatch()->getParticipation1()->getSetsMarques() + $score1);
        $rencontre->getMatch()->getParticipation2()->setSetsEncaisses($rencontre->getMatch()->getParticipation2()->getSetsEncaisses() + $score1);

        $rencontre->getMatch()->getParticipation2()->setSetsMarques($rencontre->getMatch()->getParticipation2()->getSetsMarques() + $score2);
        $rencontre->getMatch()->getParticipation1()->setSetsEncaisses($rencontre->getMatch()->getParticipation1()->getSetsEncaisses() + $score2);

        $scoreDetail = self::recupererDetailEquipeSportsSets($detail);

        $pointsEquipe1 = array_sum($scoreDetail[0]);
        $pointsEquipe2 = array_sum($scoreDetail[1]);

        $rencontre->getMatch()->getParticipation1()->setPointsMarques($rencontre->getMatch()->getParticipation1()->getPointsMarques() + $pointsEquipe1);
        $rencontre->getMatch()->getParticipation1()->setPointsEncaisses($rencontre->getMatch()->getParticipation1()->getPointsEncaisses() + $pointsEquipe2);
        $rencontre->getMatch()->getParticipation2()->setPointsMarques($rencontre->getMatch()->getParticipation2()->getPointsMarques() + $pointsEquipe2);
        $rencontre->getMatch()->getParticipation2()->setPointsEncaisses($rencontre->getMatch()->getParticipation1()->getPointsEncaisses() + $pointsEquipe1);

        if ($score1 > $score2) {
            $rencontre->getMatch()->setScoreTeam1($rencontre->getMatch()->getScoreTeam1() + 1);
            $rencontre->getMatch()->setScoreTeam2($rencontre->getMatch()->getScoreTeam2() + 0);
        } elseif ($score1 < $score2) {
            $rencontre->getMatch()->setScoreTeam2($rencontre->getMatch()->getScoreTeam2() + 1);
            $rencontre->getMatch()->setScoreTeam1($rencontre->getMatch()->getScoreTeam1() + 0);
        }
    }

    public static function recupererDetailEquipeSportsSets($detail)
    {
        $detailExplode = [];
        $details = explode(" ", $detail);
        foreach ($details as $detail) {
            $detailExplode[] = explode("/", $detail);
        }

        for ($i = 0; $i < sizeof($detailExplode); $i++) {
            $scoresDetail1[] = $detailExplode[$i][0];
            $scoresDetail2[] = $detailExplode[$i][1];
        }

        $scoreDetails[] = $scoresDetail1;
        $scoreDetails[] = $scoresDetail2;
        return $scoreDetails;
    }

    public static function recupererPointsSetsEquipeSportsSets($detail)
    {
        $detailExplode = [];
        $details = explode(" ", $detail);
        foreach ($details as $detail) {
            $detailExplode[] = explode("/", $detail);
        }

        return $detailExplode;
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


    public static function genererResultatsMatchsAleatoire($matchs)
    {
        foreach ($matchs as $match) {
            self::setResult($match, rand(0, 30), rand(0, 30));
        }
    }

    public function classerParPoints($participations)
    {
        usort($participations, function ($a, $b) {
            $ad = $a->getPointsClassement();
            $bd = $b->getPointsClassement();
            if ($ad == $bd) {
                $match = $this->mr->findMatchWithAnEventand2Participations($a->getEvent(), $a, $b);
                if ($match[0]->getWinner() === $a) {
                    return -1;
                } elseif ($match[0]->getWinner() === $b) {
                    return 1;
                } else {
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
                }

            } else {
                return $ad > $bd ? -1 : 1;
            }
        });
        return $participations;
    }

    public function classerParPointsPoules($participations, $poules)
    {
        for ($j = 0; $j < sizeof($poules); $j++) {
            dump($j);
            usort($participations[$j], function($a, $b){
                $ad = $a->getPointsClassement();
                $bd = $b->getPointsClassement();
                if ($ad == $bd) {
                    $match = $this->mr->findMatchWithAnEventand2Participations($a->getEvent(), $a, $b);
                    if ($match[0]->getWinner() === $a) {
                        return -1;
                    } elseif ($match[0]->getWinner() === $b) {
                        return 1;
                    } else {
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
                    }
                } else {
                    return $ad > $bd ? -1 : 1;
                }

            });
        }
        return $participations;
    }

    public static function creationPhase($event, $round)
    {
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