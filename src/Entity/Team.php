<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Team extends Participant
{

    public function __construct()
    {
        $this->teamCreated = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamCreated", mappedBy="team")
     */
    private $teamCreated;

}
