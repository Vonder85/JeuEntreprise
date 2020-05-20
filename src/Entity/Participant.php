<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="participant")
     */
    private $participation;

    /**
     * @return mixed
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param mixed $participation
     */
    public function setParticipation($participation): void
    {
        $this->participation = $participation;
    }
    
}
