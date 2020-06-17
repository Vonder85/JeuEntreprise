<?php


namespace App\Utils;


use App\Entity\Match;
use App\Entity\Participation;

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

    public static function participationsDeuxderniersPoule($poules, $participationsPoule, $event){
        //Récupérer les deux derniers de chaque poule
        $participations = [];
        for($i=0; $i<sizeof($poules); $i++){
            for($j=2; $j>0;$j--){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($participationsPoule[$i][sizeof($participationsPoule[$i])-$j]->getParticipant());
                $participations[] = $participation;

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
        } elseif($nbPoule == 4 && (sizeof($participations) === 17 || sizeof($participations) === 21)){
            //Création de la poule de la plus conséquente en premier
            for($i=0; $i< $nbPoule;$i++){
                $poules[] = array_slice($participations, 0, ($count + ($i===0 ? 1 : 0)));
                array_splice($participations, 0, ($count + ($i===0 ? 1 : 0)));
            }
        }elseif(sizeof($participations) === 19) {
            // Création de la poule de 4 puis des poules de 5
                for($i=0; $i < $nbPoule; $i++){
                    $poules[] = array_slice($participations, 0, ($count + ($i===$nbPoule-1 ? 0 : 1)));
                    array_splice($participations, 0, ($count + ($i=== $nbPoule-1 ? 0 : 1)));
                }
        }else {
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

    public static function participations2premiersPoule($participationsPoule, $event){
        $participations = [];
        //Récupère les 2 premiers de chaque poule
        for($j=0; $j<sizeof($participationsPoule);$j++){
            for($i=0; $i < 2; $i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($participationsPoule[$j][$i]->getParticipant());
                $participations[] = $participation;
            }
        }
        return $participations;
    }

    public static function creerParticipationsHuitemeFinale16Equipes($participationsPoule, $event){
        $participations = [];
        //Récupère les 4 premiers de chaque poule
        for($j=0; $j<sizeof($participationsPoule);$j++){
            for($i=0; $i < 4; $i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($participationsPoule[$j][$i]->getParticipant());
                $participations[] = $participation;
            }
        }
        return $participations;
    }

    public static function creerPoule5emePlace($participations){
        //Enlever les 4 premiers (1/2 finale)
        array_splice($participations, 0, 4);
        return $participations;
    }

    public static function creerPoule3emePlace($event, $participationsDebut, $matchs){
        $participations = [];
        //Récupération du dernier de la phase des matchs de poule
        $participation = new Participation();
        $participation->setEvent($event);
        $participation->setParticipant($participationsDebut[sizeof($participationsDebut) -1]->getParticipant());

        //Récupération des deux perdants des demi
        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event);
            $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
        }
        return $participations;
    }

    public static function recupererEquipePourCreationBarrage($participationsPoule){
        $participations = [];
        for ($j = 0; $j < 2; $j++) {
            for ($i = 1; $i < 3; $i++) {
                $participations[] = $participationsPoule[$j][$i];
            }
        }
        dump($participations);
        return $participations;
    }

    public static function creerMatchsPhaseFinale($matchs, $event, $roundName){
        $participations = [];
        if ($roundName == "1/2 finale") {
            for ($i = 0; $i < sizeof($matchs); $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }

            for ($i = 0; $i < sizeof($matchs); $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        } elseif($roundName === "Finale" || $roundName === "7ème place") {
            for ($i = 0; $i < 2; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "9ème place"){
            for ($i = 0; $i < 2; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "1/2 finale consolante" ){
            for ($i=0; $i<sizeof($matchs);$i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "1/4 finale consolante"){
            for ($i=0; $i<sizeof($matchs);$i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "1/4 finale"){
            for ($i=0; $i<sizeof($matchs);$i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
        }
        return $participations;
    }

    public static function creerMatchsPhaseFinaleConsolanteAPartir15Equipes($matchs, $event, $roundName){
        $participations = [];

        if($roundName === "9ème place"){
            for ($i = 0; $i < 2; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "11ème place" ){
            for ($i=0; $i<2;$i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "13ème place"){
            for ($i = 2; $i < 4; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "15ème place"){
            for ($i = 2; $i < 4; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "17ème place"){
            for ($i = 4; $i < 6; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "19ème place"){
            for ($i = 4; $i < 6; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        }elseif($roundName === "1/2 finale consolante"){
            for ($i=0; $i<sizeof($matchs);$i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
                $participations[] = $participation;
            }
            for ($i=0; $i<sizeof($matchs);$i++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
                $participations[] = $participation;
            }
        }
        return $participations;
    }

    public static function creer3et5EmePlace($roundName, $event, $participations){
        $participations = [];
        $k=0;
        if($roundName === "5ème place"){
            $k = 4;
        }elseif($roundName === "3ème place"){
            $k=2;
        }

        for ($i = 0; $i < 2; $i++) {

            $participation = new Participation();
            $participation->setEvent($event);
            $participation->setParticipant($participations[$k]->getParticipant());
            $participations[] = $participation;
            $k++;
        }
        return $participations;
    }

    public static function creerPhasesDemiFinale($participationsPoule, $event, $poules, $participations){
        $participationsDemi = [];
        if (sizeof($participations) !== 14) {
            $participationsA = [];
            for ($j = 0; $j < 2; $j++) {
                for ($i = 0; $i < 2; $i++) {
                    $participationsA[] = $participationsPoule[$j][$i];
                }

            }
            for ($i = 0; $i < 4; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($participationsA[$i]->getParticipant());
                $participationsDemi[] = $participation;
            }


            $participationsB = [];
            for ($j = 0; $j < 2; $j++) {
                for ($i = 2; $i < 4; $i++) {
                    $participationsB[] = $participationsPoule[$j][$i];
                }

            }
            for ($i = 0; $i < 4; $i++) {
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($participationsB[$i]->getParticipant());
                $participationsDemi[] = $participation;
            }



            $participationsC = [];
            if (sizeof($participations) === 12) {
                for ($j = 0; $j < 2; $j++) {
                    for ($i = 4; $i < 6; $i++) {
                        $participationsC[] = $participationsPoule[$j][$i];
                    }
                }
            }

            if (sizeof($participations) === 13) {
                $participationsPoule[0][sizeof($participationsPoule[0]) - 1]->setPositionClassement(13);
                array_splice($participationsPoule[0], sizeof($participationsPoule[0]) - 1, 1);
                for ($j = 0; $j < 2; $j++) {
                    for ($i = 4; $i < 6; $i++) {
                        $participationsC[] = $participationsPoule[$j][$i];
                    }
                }
            }
            if (sizeof($participationsC) > 0) {
                for ($i = 0; $i < 4; $i++) {
                    $participation = new Participation();
                    $participation->setEvent($event);
                    $participation->setParticipant($participationsC[$i]->getParticipant());
                    $participationsDemi[] = $participation;
                }
            }

        }
        return $participationsDemi;
    }

    public static function recupParticipationsAvecLoosersde2Matchs($matchs, $event){
        $participations = [];
        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event);
            $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
            $participations[] = $participation;
        }
        return $participations;
    }

    public static function recupParticipationsAvecWinnersde2Matchs($matchs, $event){
        $participations = [];
        for ($i = 0; $i < 2; $i++) {
            $participation = new Participation();
            $participation->setEvent($event);
            $participation->setParticipant($matchs[$i]->getWinner()->getParticipant());
            $participations[] = $participation;
        }
        return $participations;
    }

    public static function recupParticipationsAvecLoosers($matchs, $event){
        $participations = [];
        for ($i = 0; $i < sizeof($matchs); $i++) {
            $participation = new Participation();
            $participation->setEvent($event);
            $participation->setParticipant($matchs[$i]->getLooser()->getParticipant());
            $participations[] = $participation;
        }
        return $participations;
    }

    public static function creerQuartFinale3Poules($participations, $event)
    {
        $matchs = [];
        $j = 0;
        //Récupère les deux première rencontres
        for ($i = 0; $i < 2; $i++) {
            $match = new Match();
            $match->setEvent($event);
            $match->setParticipation1($participations[$j]);
            $match->setParticipation2($participations[$j+3]);
            $j = 2;
            $matchs[] = $match;
        }
        //Puis le dernier match
        $match= new Match();
        $match->setEvent($event);
        $match->setParticipation1($participations[4]);
        $match->setParticipation2($participations[1]);
        $matchs[] = $match;

        return $matchs;
    }

    public static function creerQuartFinaleAPArtir15Equipes($participations, $event){
        $matchs = [];
        //pour les trois premiers match des quarts
        for($i=0; $i < 4; $i++){
            if($i === 1){
                $match = new Match();
                $match->setEvent($event);
                $match->setParticipation1($participations[$i]);
                $match->setParticipation2($participations[4]);
                $matchs[] = $match;
            }else{
                $match = new Match();
                $match->setEvent($event);
                $match->setParticipation1($participations[$i]);
                $match->setParticipation2($participations[sizeof($participations) - ($i === 0? 1: $i)]);
                $matchs[] = $match;
            }
        }

        return $matchs;
    }

    public static function participations17emeplace($participationsPoule){
        $derniers = [];
        $participations= [];
        //recupération des 3 derniers
        for($i=0; $i < sizeof($participationsPoule);$i++){
            $derniers[] = $participationsPoule[$i][sizeof($participationsPoule[$i])-1];
        }
        //recupération des 2 moins bon
        $derniers = EventUtils::classerParPoints($derniers);
        for($i=1;$i<sizeof($derniers);$i++){
            $participations[] = $derniers[$i];
        }
        return $participations;
    }

    public static function participationsQuartFinaleConsolante18equipes($participationsPoule, $event){
        $participations = [];
        $troisiemes= [];
        $sixiemes = [];
        //Récupération du 3ème des 3èmes
        for($i=0; $i<sizeof($participationsPoule);$i++){
            $troisiemes[] = $participationsPoule[$i][2];
        }
        $troisiemes = EventUtils::classerParPoints($troisiemes);
        $dernierTroisieme = $troisiemes[2];
        array_push($participations,$dernierTroisieme);

        //Récupération des 4eme et 5eme
        for($i=0;$i<sizeof($participationsPoule);$i++){
            for($j=3;$j<5;$j++){
                $participations[]= $participationsPoule[$i][$j];
            }
        }

        //Récupération du meilleur 6eme
        for($i=0; $i<sizeof($participationsPoule);$i++){
            $sixiemes[] = $participationsPoule[$i][sizeof($participationsPoule[$i])-1];
        }
        $sixiemes = EventUtils::classerParPoints($sixiemes);
        $premierSixieme = $sixiemes[0];
        array_push($participations,$premierSixieme);

        foreach ($participations as $particip){
            $participation = new Participation();
            $participation->setEvent($event);
            $participation->setParticipant($particip->getParticipant());

            $participations[] = $participation;
        }
        return $participations;
    }

    public static function participations3emeet4emePoule7($participationsPoule, $event){
        $participations = [];
        //Récupération des 3eme et 4eme de chaque poule
        for($i=0; $i < sizeof($participationsPoule); $i++){
            for($j=2; $j<4;$j++){
                if($i === 1){
                    $participation = new Participation();
                    $participation->setEvent($event);
                    $participation->setParticipant($participationsPoule[$i][$j]->getParticipant());
                    $participations[] = $participation;
                    $j++;
                }else{
                    $participation = new Participation();
                    $participation->setEvent($event);
                    $participation->setParticipant($participationsPoule[$i][$j]->getParticipant());
                    $participations[] = $participation;
                }
            }
        }

        return $participations;
    }

    public static function participations3emeet4eme($participationsPoule, $event){
        $participations = [];
        //Récupération des 3eme et 4eme de chaque poule
        for($i=0; $i < sizeof($participationsPoule); $i++){
            for($j=2; $j<4;$j++){
                $participation = new Participation();
                $participation->setEvent($event);
                $participation->setParticipant($participationsPoule[$i][$j]->getParticipant());
                $participations[] = $participation;
            }
        }
        return $participations;
    }

    public static function participations5emeChaquePoule($participationsPoule){
        $participations = [];
        //Récupération du dernier de chaque poule
        for($i=0; $i < sizeof($participationsPoule); $i++){
               array_push($participations, $participationsPoule[$i][4]);
        }

        return $participations;
    }
}