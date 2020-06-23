<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RencontreRepository::class)
 */
class Rencontre
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

    /**
     * @return mixed
     */
    public function getScoreTeam1()
    {
        return $this->scoreTeam1;
    }

    /**
     * @param mixed $scoreTeam1
     */
    public function setScoreTeam1($scoreTeam1): void
    {
        $this->scoreTeam1 = $scoreTeam1;
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

    /**
     * @return mixed
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * @param mixed $heure
     */
    public function setHeure($heure): void
    {
        $this->heure = $heure;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Match", inversedBy="rencontres")
     */
    private $match;

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

}
