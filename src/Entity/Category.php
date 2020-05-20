<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
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
        $this->events = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="category")
     */
    private $events;

    /**
     * @return ArrayCollection
     */
    public function getEvents(): ArrayCollection
    {
        return $this->events;
    }

    /**
     * @param ArrayCollection $events
     */
    public function setEvents(ArrayCollection $events): void
    {
        $this->events = $events;
    }

}
