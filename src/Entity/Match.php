<?php

namespace App\Entity;

use App\Repository\MatchRepository;
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
    private $scoreEquipe2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $heure;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $round;

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

    public function getScoreEquipe2(): ?int
    {
        return $this->scoreEquipe2;
    }

    public function setScoreEquipe2(?int $scoreEquipe2): self
    {
        $this->scoreEquipe2 = $scoreEquipe2;

        return $this;
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

    public function getRound(): ?string
    {
        return $this->round;
    }

    public function setRound(?string $round): self
    {
        $this->round = $round;

        return $this;
    }
}
