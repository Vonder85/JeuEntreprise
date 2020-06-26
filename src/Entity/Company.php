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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goldMedal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $silverMedal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bronzeMedal;

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
     * @return mixed
     */
    public function getGoldMedal()
    {
        return $this->goldMedal;
    }

    /**
     * @param mixed $goldMedal
     */
    public function setGoldMedal($goldMedal): void
    {
        $this->goldMedal = $goldMedal;
    }

    /**
     * @return mixed
     */
    public function getSilverMedal()
    {
        return $this->silverMedal;
    }

    /**
     * @param mixed $silverMedal
     */
    public function setSilverMedal($silverMedal): void
    {
        $this->silverMedal = $silverMedal;
    }

    /**
     * @return mixed
     */
    public function getBronzeMedal()
    {
        return $this->bronzeMedal;
    }

    /**
     * @param mixed $bronzeMedal
     */
    public function setBronzeMedal($bronzeMedal): void
    {
        $this->bronzeMedal = $bronzeMedal;
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
