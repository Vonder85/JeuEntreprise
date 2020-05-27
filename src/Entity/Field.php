<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FieldRepository")
 */
class Field
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


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

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
        $this->meets = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Match", mappedBy="field", cascade={"remove"})
     */
    private $matchs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="field", cascade={"remove"})
     *
     */
    private $meets;

    /**
     * @return ArrayCollection
     */
    public function getMatchs(): ArrayCollection
    {
        return $this->matchs;
    }

    /**
     * @param ArrayCollection $matchs
     */
    public function setMatchs(ArrayCollection $matchs): void
    {
        $this->matchs = $matchs;
    }

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
