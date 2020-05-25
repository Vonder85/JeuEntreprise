<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
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

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $country;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function __construct()
    {
        $this->athlets = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Athlet", mappedBy="company", cascade={"remove"})
     */
    private $athlets;

    /**
     * @return ArrayCollection
     */
    public function getAthlets(): ArrayCollection
    {
        return $this->athlets;
    }

    /**
     * @param ArrayCollection $athlets
     */
    public function setAthlets(ArrayCollection $athlets): void
    {
        $this->athlets = $athlets;
    }
}
