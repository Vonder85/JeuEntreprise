<?php

namespace App\Entity;

use App\Repository\MatchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchRepository::class)
 * @ORM\Table(name="`match`")
 */
class Match
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
    private $scoreTeam1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $scoreTeam2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $heure;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $field;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $detail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreTeam1(): ?int
    {
        return $this->scoreTeam1;
    }

    public function setScoreTeam1(?int $scoreTeam1): self
    {
        $this->scoreTeam1 = $scoreTeam1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getScoreTeam2()
    {
        return $this->scoreTeam2;
    }

    /**
     * @param mixed $scoreTeam2
     */
    public function setScoreTeam2($scoreTeam2): void
    {
        $this->scoreTeam2 = $scoreTeam2;
    }



    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(?\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participation", cascade={"remove"})
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participation", cascade={"remove"})
     */
    private $looser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participation", cascade={"remove"})
     */
    private $participation1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participation", cascade={"remove"})
     */
    private $participation2;

    /**
     * @return mixed
     */
    public function getParticipation2()
    {
        return $this->participation2;
    }

    /**
     * @param mixed $participation2
     */
    public function setParticipation2($participation2): void
    {
        $this->participation2 = $participation2;
    }
    
    /**
     * @return mixed
     */
    public function getParticipation1()
    {
        return $this->participation1;
    }

    /**
     * @param mixed $participation1
     */
    public function setParticipation1($participation1): void
    {
        $this->participation1 = $participation1;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner): void
    {
        $this->winner = $winner;
    }

    /**
     * @return mixed
     */
    public function getLooser()
    {
        return $this->looser;
    }

    /**
     * @param mixed $looser
     */
    public function setLooser($looser): void
    {
        $this->looser = $looser;
    }



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
