<?php

namespace App\Entity;

use App\Repository\TeamCreatedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamCreatedRepository::class)
 */
class TeamCreated
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Athlet", inversedBy="teamCreated")
     */
    protected $athlet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="teamCreated")
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

}
