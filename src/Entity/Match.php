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
     * @ORM\ManyToOne(targetEntity="App\Entity\Field", inversedBy="matchs")
     */
    private $field;

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

    public function __construct()
    {
        $this->meets = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="match")
     */
    private $meets;

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

}
