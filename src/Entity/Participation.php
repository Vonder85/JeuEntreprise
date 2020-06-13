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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goldMedal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $silverMedal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bronzeMedal;

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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $positionClassement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pointsMarques;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pointsEncaisses;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getGoldMedal()
    {
        return $this->goldMedal;
    }

    /**
     * @param mixed $goldMedal
     */
    public function setGoldMedal($goldMedal): void
    {
        $this->goldMedal = $goldMedal;
    }

    /**
     * @return mixed
     */
    public function getSilverMedal()
    {
        return $this->silverMedal;
    }

    /**
     * @param mixed $silverMedal
     */
    public function setSilverMedal($silverMedal): void
    {
        $this->silverMedal = $silverMedal;
    }

    /**
     * @return mixed
     */
    public function getBronzeMedal()
    {
        return $this->bronzeMedal;
    }

    /**
     * @param mixed $bronzeMedal
     */
    public function setBronzeMedal($bronzeMedal): void
    {
        $this->bronzeMedal = $bronzeMedal;
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
     * @return mixed
     */
    public function getPositionClassement()
    {
        return $this->positionClassement;
    }

    /**
     * @param mixed $positionClassement
     */
    public function setPositionClassement($positionClassement): void
    {
        $this->positionClassement = $positionClassement;
    }

    /**
     * @return mixed
     */
    public function getPointsMarques()
    {
        return $this->pointsMarques;
    }

    /**
     * @param mixed $pointsMarques
     */
    public function setPointsMarques($pointsMarques): void
    {
        $this->pointsMarques = $pointsMarques;
    }

    /**
     * @return mixed
     */
    public function getPointsEncaisses()
    {
        return $this->pointsEncaisses;
    }

    /**
     * @param mixed $pointsEncaisses
     */
    public function setPointsEncaisses($pointsEncaisses): void
    {
        $this->pointsEncaisses = $pointsEncaisses;
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
