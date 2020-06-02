<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $meridianBreak;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $breakRest;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrFields;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startAt;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getMeridianBreak(): ?int
    {
        return $this->meridianBreak;
    }

    public function setMeridianBreak(?int $meridianBreak): self
    {
        $this->meridianBreak = $meridianBreak;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getBreakRest(): ?int
    {
        return $this->breakRest;
    }

    public function setBreakRest(?int $breakRest): self
    {
        $this->breakRest = $breakRest;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbrFields()
    {
        return $this->nbrFields;
    }

    /**
     * @param mixed $nbrFields
     */
    public function setNbrFields($nbrFields): void
    {
        $this->nbrFields = $nbrFields;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param mixed $startAt
     */
    public function setStartAt($startAt): void
    {
        $this->startAt = $startAt;
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="events")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="events")
     */
    private $type;



    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    public function __construct()
    {
        $this->participation = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="event", orphanRemoval=true)
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Competition", inversedBy="events")
     */
    private $competition;

    /**
     * @return mixed
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * @param mixed $competition
     */
    public function setCompetition($competition): void
    {
        $this->competition = $competition;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Discipline", inversedBy="events")
     */
    private $discipline;

    /**
     * @return mixed
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * @param mixed $discipline
     */
    public function setDiscipline($discipline): void
    {
        $this->discipline = $discipline;
    }

}
