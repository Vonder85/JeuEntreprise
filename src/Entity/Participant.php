<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="participant")
     */
    private $participation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Athlet", inversedBy="participant")
     */
    protected $athlet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="participant")
     */
    protected $team;

    /**
     * @return mixed
     */
    public function getAthlet()
    {
        return $this->athlet;
    }

    /**
     * @param mixed $athlet
     */
    public function setAthlet($athlet): void
    {
        $this->athlet = $athlet;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team): void
    {
        $this->team = $team;
    }


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
