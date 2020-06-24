<?php

namespace App\Entity;

use App\Repository\DisciplineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DisciplineRepository")
 */
class Discipline
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $multiMatchs;

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
     * @return mixed
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @param mixed $sets
     */
    public function setSets($sets): void
    {
        $this->sets = $sets;
    }

    /**
     * @return mixed
     */
    public function getMultiMatchs()
    {
        return $this->multiMatchs;
    }

    /**
     * @param mixed $multiMatchs
     */
    public function setMultiMatchs($multiMatchs): void
    {
        $this->multiMatchs = $multiMatchs;
    }

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="discipline")
     */
    private $events;

    /**
     * @return ArrayCollection
     */
    public function getEvents(): ArrayCollection
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     */
    public function setEvents(ArrayCollection $events): void
    {
        $this->events = $events;
    }

}
