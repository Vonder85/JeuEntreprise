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

            for ($e = 0; $e < sizeof($tabIdsParticipations); $e++) {
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

                for ($e = 0; $e < sizeof($participations[$m]); $e++) {
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

    public static function generateRencontresAllerRetour($tabIdsParticipations, $event)
    {
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

    public static function creerDemiFinale($participations, $event)
    {
        $matchs = [];
        $j = 0;
        for ($i = 0; $i < sizeof($participations)/2; $i++) {
            $match = new Match();
            $match->setEvent($event);
            $match->setParticipation1($participations[$j]);
            $match->setParticipation2($participations[sizeof($participations)-($j+1)]);
            $j++;
            $matchs[] = $match;
        }
        return $matchs;
    }

    public static function creerMatch1vs1($participations, $event)
    {
        $j = 0;
        $match = new Match();
        $match->setEvent($event);
        $match->setParticipation1($participations[$j]);
        $match->setParticipation2($participations[$j + 1]);

        return $match;
    }

    public static function affectationTerrainsPoules($rencontres, $nbrTerrains, $event,$aPartir){
        $timeToAdd = $event->getDuration() + $event->getBreakRest();
        //get array of fields
        $j= $aPartir;
        for ($i = 0; $i < $nbrTerrains; $i++) {
            $fields[$i] = $j;
            $j++;
        }
        $numeroPhase = 1;
        $phases = [];
        $date = clone ($event->getStartAt());

        do {
            $phases[$numeroPhase] = [];
            $k = 0;
            do {
                //Création phase de rencontres
                if (!(in_array('ok',RencontreUtils::equipePresente($phases[$numeroPhase], $rencontres[$k])))) {
                    $rencontres[$k]->setField($fields[0]);
                    $rencontres[$k]->setHeure(clone($date));
                    array_splice($fields, 0, 1);
                    array_push($phases[$numeroPhase], $rencontres[$k]);
                    array_splice($rencontres, $k, 1);
                }  else {
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
            $j = $aPartir;
            for ($i = 0; $i < $nbrTerrains; $i++) {
                $fields[$i] = $j;
                $j++;
            }
        } while (!empty($rencontres));
}

    public static function nbrTerrains($nbTerrains, $totalParticipations){
        if ($nbTerrains > floor(sizeof($totalParticipations) / 2)) {
            $nbTerrains = floor(sizeof($totalParticipations) / 2);
        }
        return $nbTerrains;
    }

    public static function affectationTerrains($rencontres, $nbrTerrains, $event, $aPartir)
    {
        $timeToAdd = $event->getDuration() + $event->getBreakRest();
        //get array of fields
        $j= $aPartir;
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
                    if (!(in_array('ok',RencontreUtils::equipePresente($phases[$numeroPhase], $rencontres[$k])))) {
                        $rencontres[$k]->setField($fields[0]);
                        $rencontres[$k]->setHeure(clone($date));
                        array_splice($fields, 0, 1);
                        array_push($phases[$numeroPhase], $rencontres[$k]);
                        array_splice($rencontres, $k, 1);
                    }  else {
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
            $j = $aPartir;
            for ($i = 0; $i < $nbrTerrains; $i++) {
                $fields[$i] = $j;
                $j++;
            }
        } while (!empty($rencontres));
    }


    public static function equipePresente($phase, $rencontre)
    {
        $resultat = [];
        $part1 = $rencontre->getParticipation1()->getId();
        $part2 = $rencontre->getParticipation2()->getId();
        if (sizeof($phase) > 0) {
            for ($i = 0; $i < sizeof($phase); $i++) {
                if ($part1 == $phase[$i]->getParticipation1()->getId() || $part1 == $phase[$i]->getParticipation2()->getId() || $part2 == $phase[$i]->getParticipation1()->getId() || $part2 == $phase[$i]->getParticipation2()->getId()) {
                    $resultat[] = 'ok';
                } else {
                    $resultat[] = 'Nok';
                }
            }

        }
        return $resultat;
    }

    public static function creerConsolante($poules, $participationsPoule){
        //Récupérer les deux derniers de chaque poule
        $participations = [];
        for($i=0; $i<sizeof($poules); $i++){
            for($j=2; $j>0;$j--){
                $participations[] = $participationsPoule[$i][sizeof($participationsPoule[$i])-$j];
            }
        }
        return $participations;
    }

    public static function creerTournoiPrincipal($poules, $participationsPoule){
        //Récupérer tous sauf les deux derniers de chaque poule
        $participations = [];
        for($i=0; $i<sizeof($poules); $i++){
            for($j=2; $j>0;$j--){
                array_splice($participationsPoule[$i], sizeof($participationsPoule[$i])-$j, 1);
            }
        }
        for($i=0; $i<sizeof($poules);$i++){
            for($j=0; $j< sizeof($participationsPoule[$i]); $j++){
                $participations[] = $participationsPoule[$i][$j];
            }
        }
        return $participations;
    }

    public static function affectationPoule($nbPoule, $participations, $count){
        if ($nbPoule == 2) {
            $j = 1;
            for ($i = 0; $i < $nbPoule; $i++) {
                if (sizeof($participations) % 2 == 1) {
                    $poules[] = array_slice($participations, 0, $count + $j);

                    array_splice($participations, 0, $count + $j);
                    $j--;
                } else {
                    $poules[] = array_slice($participations, 0, $count);
                    array_splice($participations, 0, $count);
                }
            }

        } elseif ($nbPoule == 3 && sizeof($participations) === 14) {

            for ($i = 0; $i < $nbPoule - 1; $i++) {
                $k = 1;
                $poules[] = array_slice($participations, 0, $count + $k);
                array_splice($participations, 0, $count + $k);
            }
            $poules[] = array_slice($participations, 0, $count);
        } else {

            for ($i = 0; $i < $nbPoule; $i++) {
                $poules[] = array_slice($participations, 0, $count);

                array_splice($participations, 0, $count);

            }

        }
        return $poules;
    }

    public static function affectationPoulesConsolante($nbPoule, $participations, $count){
        $poules = [];
        $h = 0;
        for ($i = 0; $i < $nbPoule; $i++) {

            for($j=0; $j<$count;$j++){
                $poules[$i][] = array_slice($participations, $h, 1);
                $h = $h+2;
            }
            $h=1;
        }
        return $poules;
    }

    public static function creerQuartFinale($participations, $event){
        $matchs = [];
        //pour les deux premiers match des quarts
        for($i=0; $i < 2; $i++){
            $match = new Match();
            $match->setEvent($event);
            $match->setParticipation1($participations[$i]);
            $match->setParticipation2($participations[$i+5]);
            $matchs[] = $match;
        }
        //Pour le 3ème match des quarts
            $match = new Match();
            $match->setEvent($event);
            $match->setParticipation1($participations[2]);
            $match->setParticipation2($participations[3]);
            $matchs[] = $match;

        //Pour le 4ème match des quarts
        $match = new Match();
        $match->setEvent($event);
        $match->setParticipation1($participations[4]);
        $match->setParticipation2($participations[7]);
        $matchs[] = $match;

        return $matchs;
    }
}