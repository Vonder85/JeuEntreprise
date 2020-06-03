<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $medal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $poule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $victory;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nul;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $defeat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pointsClassement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedal(): ?string
    {
        return $this->medal;
    }

    public function setMedal(?string $medal): self
    {
        $this->medal = $medal;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoule()
    {
        return $this->poule;
    }

    /**
     * @param mixed $poule
     */
    public function setPoule($poule): void
    {
        $this->poule = $poule;
    }

    /**
     * @return mixed
     */
    public function getVictory()
    {
        return $this->victory;
    }

    /**
     * @param mixed $victory
     */
    public function setVictory($victory): void
    {
        $this->victory = $victory;
    }

    /**
     * @return mixed
     */
    public function getNul()
    {
        return $this->nul;
    }

    /**
     * @param mixed $nul
     */
    public function setNul($nul): void
    {
        $this->nul = $nul;
    }

    /**
     * @return mixed
     */
    public function getDefeat()
    {
        return $this->defeat;
    }

    /**
     * @param mixed $defeat
     */
    public function setDefeat($defeat): void
    {
        $this->defeat = $defeat;
    }

    /**
     * @return mixed
     */
    public function getPointsClassement()
    {
        return $this->pointsClassement;
    }

    /**
     * @param mixed $pointsClassement
     */
    public function setPointsClassement($pointsClassement): void
    {
        $this->pointsClassement = $pointsClassement;
    }

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match): void
    {
        $this->match = $match;
    }



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match", mappedBy="participation1", cascade={"remove"})
     */
    private $match;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant", inversedBy="participation")
     */
    protected $participant;

    /**
     * @return mixed
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param mixed $participant
     */
    public function setParticipant($participant): void
    {
        $this->participant = $participant;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="participation")
     */
    protected $event;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event): void
    {
        $this->event = $event;
    }

}
