<?php

use App\Entity\Event;

class RencontreUtilsTest extends \PHPUnit\Framework\TestCase{

    public function testGenerateRencontreCount(){
        $event = new Event();
        $part1 = new \App\Entity\Participant();
        $part2 = new \App\Entity\Participant();
        $part3 = new \App\Entity\Participant();
        $part4 = new \App\Entity\Participant();
        $part5 = new \App\Entity\Participant();
        $part6 = new \App\Entity\Participant();

        $participations = [$part1, $part2, $part3, $part4, $part5, $part6];

        $this->assertCount(15, \App\Utils\RencontreUtils::generateRencontres($participations, $event));
    }

    public function testGenerateRencontre(){
        $event = new Event();
        $matchs = [];

        $part1 = new \App\Entity\Participant();
        $part2 = new \App\Entity\Participant();
        $part3 = new \App\Entity\Participant();
        $part4 = new \App\Entity\Participant();
        $participations = [$part1, $part2, $part3, $part4];

        $match1 = new \App\Entity\Match();
        $match1->setParticipation1($part1);
        $match1->setParticipation2($part4);
        $matchs[] = $match1;

        $match2 = new \App\Entity\Match();
        $match2->setParticipation1($part2);
        $match2->setParticipation2($part3);
        $matchs[] = $match2;

        $match3 = new \App\Entity\Match();
        $match3->setParticipation1($part1);
        $match3->setParticipation2($part3);
        $matchs[] = $match3;

        $match4 = new \App\Entity\Match();
        $match4->setParticipation1($part4);
        $match4->setParticipation2($part2);
        $matchs[] = $match4;

        $match5 = new \App\Entity\Match();
        $match5->setParticipation1($part1);
        $match5->setParticipation2($part2);
        $matchs[] = $match5;

        $match6 = new \App\Entity\Match();
        $match6->setParticipation1($part3);
        $match6->setParticipation2($part4);
        $matchs[] = $match6;

        $this->assertSameSize($matchs, \App\Utils\RencontreUtils::generateRencontres($participations, $event));
    }

    public function testGenerateRencontresAllerRetourCount(){
        $event = new Event();
        $part1 = new \App\Entity\Participant();
        $part2 = new \App\Entity\Participant();
        $part3 = new \App\Entity\Participant();
        $part4 = new \App\Entity\Participant();
        $part5 = new \App\Entity\Participant();


        $participations = [$part1, $part2, $part3, $part4, $part5];

        $this->assertCount(16, \App\Utils\RencontreUtils::generateRencontresAllerRetour($participations, $event));
    }
}
