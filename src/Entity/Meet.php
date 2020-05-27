<?php

namespace App\Entity;

use App\Repository\MeetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetRepository::class)
 */
class Meet
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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participation", inversedBy="meets")
     */
    protected $participation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Match", inversedBy="meets")
     */
    protected $match;

    /**
     * @return mixed
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param mixed $participation
     */
    public function setParticipation($participation): void
    {
        $this->participation = $participation;
    }

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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Round", inversedBy="meets")
     */
    private $round;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Field", inversedBy="meets")
     */
    private $field;

    /**
     * @return mixed
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @param mixed $round
     */
    public function setRound($round): void
    {
        $this->round = $round;
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

    public function offsetExists($offset)
    {

        return array_key_exists($offset, [$this->getId()] );
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new \RuntimeException("Offset '$offset' does not exist !");
        }

        return $this->id[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->id[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->id[$offset]);
    }
}
