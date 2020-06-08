<?php


namespace App\Utils;


use App\Entity\Match;

class RencontreUtils
{
    public static function generateRencontres($tabIdsParticipations, $event)
    {
        $matchs = [];

        if (sizeof($tabIdsParticipations) % 2 == 1) {
            $nbMatchs = sizeof($tabIdsParticipations) / 2;

            for ($e = 0; $e < sizeof($tabIdsParticipations) - 1; $e++) {
                $l = 0;

                for ($i = 0; $i < $nbMatchs; $i++) {

                    if ($tabIdsParticipations[$l] === $tabIdsParticipations[sizeof($tabIdsParticipations) - $l - 1]) {

                    } else {
                        $match = new Match();

                        $match->setEvent($event);
                        $match->setParticipation1($tabIdsParticipations[$l]);
                        $l++;
                        $match->setParticipation2($tabIdsParticipations[sizeof($tabIdsParticipations) - $l]);
                        $matchs[] = $match;
                    }
                }
                $milieuTableau = array_slice($tabIdsParticipations, 0, 1);
                array_splice($tabIdsParticipations, 0, 1);

                $tabIdsParticipations = array_merge($tabIdsParticipations, $milieuTableau);
            }
        } else {
            $nbMatchs = sizeof($tabIdsParticipations) / 2;
            for ($e = 0; $e < sizeof($tabIdsParticipations) - 1; $e++) {
                $k = 0;

                for ($i = 0; $i < $nbMatchs; $i++) {

                    $match = new Match();

                    $match->setEvent($event);
                    $match->setParticipation1($tabIdsParticipations[$k]);
                    $k++;
                    $match->setParticipation2($tabIdsParticipations[sizeof($tabIdsParticipations) - $k]);
                    $matchs[] = $match;
                }
                $milieuTableau = array_slice($tabIdsParticipations, 1, sizeof($tabIdsParticipations) - 2);
                array_splice($tabIdsParticipations, 1, sizeof($tabIdsParticipations) - 2);

                $tabIdsParticipations = array_merge($tabIdsParticipations, $milieuTableau);
            }
        }
        return $matchs;
    }

    public static function generateRencontresPoules($participations, $event)
    {
        $matchs = [];
        for ($m = 0; $m < sizeof($participations); $m++) {

            if (sizeof($participations[$m]) % 2 == 1) {
                $nbMatchs = sizeof($participations[$m]) / 2;

                for ($e = 0; $e < sizeof($participations[$m]) - 1; $e++) {
                    $l = 0;

                    for ($i = 0; $i < $nbMatchs; $i++) {

                        if ($participations[$m][$l] === $participations[$m][sizeof($participations[$m]) - $l - 1]) {

                        } else {
                            $match = new Match();

                            $match->setEvent($event);
                            $match->setParticipation1($participations[$m][$l]);
                            $l++;
                            $match->setParticipation2($participations[$m][sizeof($participations[$m]) - $l]);
                            $matchs[] = $match;
                        }
                    }
                    $milieuTableau = array_slice($participations[$m], 0, 1);
                    array_splice($participations[$m], 0, 1);

                    $participations[$m] = array_merge($participations[$m], $milieuTableau);
                }
            } else {
                $nbMatchs = sizeof($participations[$m]) / 2;
                for ($e = 0; $e < sizeof($participations[$m]) - 1; $e++) {
                    $k = 0;
                    for ($i = 0; $i < $nbMatchs; $i++) {

                        $match = new Match();

                        $match->setEvent($event);
                        $match->setParticipation1($participations[$m][$k]);
                        $k++;
                        $match->setParticipation2($participations[$m][sizeof($participations[$m]) - $k]);
                        $matchs[] = $match;
                    }
                    $milieuTableau = array_slice($participations[$m], 1, sizeof($participations[$m]) - 2);
                    array_splice($participations[$m], 1, sizeof($participations[$m]) - 2);

                    $participations[$m] = array_merge($participations[$m], $milieuTableau);
                }

            }
        }
        return $matchs;
    }

    public static function generateRencontresAllerRetour($tabIdsParticipations, $event){
        $matchs = [];

        for ($m = 0; $m < 2; $m++) {

            if (sizeof($tabIdsParticipations) % 2 == 1) {
                $nbMatchs = sizeof($tabIdsParticipations) / 2;

                for ($e = 0; $e < sizeof($tabIdsParticipations) - 1; $e++) {
                    $l = 0;

                    for ($i = 0; $i < $nbMatchs; $i++) {

                        if ($tabIdsParticipations[$l] === $tabIdsParticipations[sizeof($tabIdsParticipations) - $l - 1]) {

                        } else {
                            $match = new Match();

                            $match->setEvent($event);
                            $match->setParticipation1($tabIdsParticipations[$l]);
                            $l++;
                            $match->setParticipation2($tabIdsParticipations[sizeof($tabIdsParticipations) - $l]);
                            $matchs[] = $match;
                        }
                    }
                    $milieuTableau = array_slice($tabIdsParticipations, 0, 1);
                    array_splice($tabIdsParticipations, 0, 1);

                    $tabIdsParticipations = array_merge($tabIdsParticipations, $milieuTableau);
                }
            } else {
                $nbMatchs = sizeof($tabIdsParticipations) / 2;
                for ($e = 0; $e < sizeof($tabIdsParticipations) - 1; $e++) {
                    $k = 0;

                    for ($i = 0; $i < $nbMatchs; $i++) {

                        $match = new Match();

                        $match->setEvent($event);
                        $match->setParticipation1($tabIdsParticipations[$k]);
                        $k++;
                        $match->setParticipation2($tabIdsParticipations[sizeof($tabIdsParticipations) - $k]);
                        $matchs[] = $match;
                    }
                    $milieuTableau = array_slice($tabIdsParticipations, 1, sizeof($tabIdsParticipations) - 2);
                    array_splice($tabIdsParticipations, 1, sizeof($tabIdsParticipations) - 2);

                    $tabIdsParticipations = array_merge($tabIdsParticipations, $milieuTableau);
                }
            }
        }
        return $matchs;;
    }

    public static function creerDemiFinalePoule4($participations, $event){
        $matchs = [];
        $j = 0;
        $k = 3;
        for($i=0;$i<2;$i++){
            $match = new Match();
            $match->setEvent($event);
            $match->setParticipation1($participations[$j]);
            $match->setParticipation2($participations[$k]);
            $j++;
            $k--;
            $matchs[] = $match;
        }
        return $matchs;
    }

    public static function creerMatch1vs1($participations, $event){
        $j = 0;
        $match = new Match();
        $match->setEvent($event);
        $match->setParticipation1($participations[$j]);
        $match->setParticipation2($participations[$j+1]);

        return $match;
    }

    public static function affectationTerrains($rencontres, $nbrTerrains, $event)
    {
        $timeToAdd = $event->getDuration() + $event->getBreakRest();
        //get array of fields
        $j = 1;
        for ($i = 0; $i < $nbrTerrains; $i++) {
            $fields[$i] = $j;
            $j++;
        }
        $numeroPhase = 1;
        $phases = [];
        $date = $event->getStartAt();
        do {
            $phases[$numeroPhase] = [];
            $k = 0;
            do {
                //Création phase de rencontres
                if (!EventUtils::equipePresente($phases[$numeroPhase], $rencontres[$k])) {
                    $rencontres[$k]->setField($fields[0]);
                    $rencontres[$k]->setHeure(clone($date));
                    array_splice($fields, 0, 1);
                    array_push($phases[$numeroPhase], $rencontres[$k]);
                    array_splice($rencontres, $k, 1);
                } else {
                    $k++;
                }
            } while ($k < sizeof($rencontres) && !empty($rencontres) && !empty($fields));

            if ($date->add(new \DateInterval('PT0H' . $timeToAdd . 'M')) > $event->getMeridianBreakHour()) {
                $date = $event->getMeridianBreakHour()->add(new \DateInterval('PT0H' . $event->getMeridianBreak() . 'M'));
            } else {
                $date->sub(new \DateInterval('PT0H' . $timeToAdd . 'M'));
                $date->add(new \DateInterval('PT0H' . $timeToAdd . 'M'));
            }

            $numeroPhase++;
            //get array of fields
            $j = 1;
            for ($i = 0; $i < $nbrTerrains; $i++) {
                $fields[$i] = $j;
                $j++;
            }
        } while (!empty($rencontres));

    }
}