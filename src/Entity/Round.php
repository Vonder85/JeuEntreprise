<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
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
        $this->meets = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meet", mappedBy="round")
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
