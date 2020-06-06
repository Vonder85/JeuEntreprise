<?php

use App\Entity\Match;
use PHPUnit\Framework\TestCase;

class EventUtilsTest extends TestCase{

    /**
     * @test
     */
    public function testGetWinner(){

        $match = new Match();
        $participant1 = new \App\Entity\Participant();
        $participant2 = new \App\Entity\Participant();

        $match->setParticipation1($participant1);
        $match->setParticipation2($participant2);

        $this->assertSame($participant1, \App\Utils\EventUtils::getWinner($match, 10, 5));
        $this->assertSame($participant2, \App\Utils\EventUtils::getWinner($match, 5, 10));
    }
}
