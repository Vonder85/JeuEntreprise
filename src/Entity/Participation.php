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
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant")
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

    public function __construct()
    {
        $this->meets = new ArrayCollection();
        $this->eventWithParticipation = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="participation")
     */
    private $meets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventWithParticipation", mappedBy="participation")
     */
    private $eventWithParticipation;

    /**
     * @return ArrayCollection
     */
    public function getMeets(): ArrayCollection
    {
        return $this->meets;
    }

    /**
     * @param ArrayCollection $meets
     */
    public function setMeets(ArrayCollection $meets): void
    {
        $this->meets = $meets;
    }

    /**
     * @return ArrayCollection
     */
    public function getEventWithParticipation(): ArrayCollection
    {
        return $this->eventWithParticipation;
    }

    /**
     * @param ArrayCollection $eventWithParticipation
     */
    public function setEventWithParticipation(ArrayCollection $eventWithParticipation): void
    {
        $this->eventWithParticipation = $eventWithParticipation;
    }



}
