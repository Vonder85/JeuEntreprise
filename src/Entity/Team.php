<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\teamRepository")
 */
class Team
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    public function __construct()
    {
        $this->participant = new ArrayCollection();
        $this->teamCreated = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant", mappedBy="team", orphanRemoval=true)
     */
    private $participant;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamCreated", mappedBy="team", cascade={"remove"})
     */
    private $teamCreated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     */
    private $company;

    /**
     * @return ArrayCollection
     */
    public function getParticipant(): ArrayCollection
    {
        return $this->participant;
    }

    /**
     * @param ArrayCollection $participant
     */
    public function setParticipant(ArrayCollection $participant): void
    {
        $this->participant = $participant;
    }

    /**
     * @return ArrayCollection
     */
    public function getTeamCreated(): ArrayCollection
    {
        return $this->teamCreated;
    }

    /**
     * @param ArrayCollection $teamCreated
     */
    public function setTeamCreated(ArrayCollection $teamCreated): void
    {
        $this->teamCreated = $teamCreated;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }

}
