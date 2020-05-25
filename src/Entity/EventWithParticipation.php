<?php

namespace App\Entity;

use App\Repository\EventWithParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventWithParticipationRepository::class)
 */
class EventWithParticipation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="eventWithParticipation")
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participation", inversedBy="eventWithParticipation")
     */
    protected $participation;

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
