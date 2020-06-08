<?php


namespace App\Data;


use App\Entity\Category;
use App\Entity\Competition;
use App\Entity\Discipline;
use App\Entity\Round;
use App\Entity\Type;

class EventCriteria
{
    /**
     * @var string
     */
    public $search = "";

    /**
     * @var Competition
     */
    public $competition = null;

    /**
     * @var Discipline
     */
    public $discipline = null;

    /**
     * @var string
     */
    public $gender = "";

    /**
     * @var Category
     */
    public $category = null;

    /**
     * @var Type
     */
    public $type = null;

    /**
     * @var Round
     */
    public $round = null;

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    /**
     * @return Competition
     */
    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    /**
     * @param Competition $competition
     */
    public function setCompetition(Competition $competition): void
    {
        $this->competition = $competition;
    }

    /**
     * @return Discipline
     */
    public function getDiscipline(): ?Discipline
    {
        return $this->discipline;
    }

    /**
     * @param Discipline $discipline
     */
    public function setDiscipline(Discipline $discipline): void
    {
        $this->discipline = $discipline;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return Type
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type $type
     */
    public function setType(Type $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Round
     */
    public function getRound(): ?Round
    {
        return $this->round;
    }

    /**
     * @param Round $round
     */
    public function setRound(Round $round): void
    {
        $this->round = $round;
    }

}