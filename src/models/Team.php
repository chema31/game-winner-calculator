<?php
namespace GWC\Models;


use GWC\Interfaces\ITeam;

class Team implements ITeam
{
    protected $name;
    protected $score = 0;

    /**
     * Initialize the object
     *
     * @param string $id
     */
    public function __construct(string $name){
        $this->name = $name;
    }

    /**
     * Get team name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Add points to score
     *
     * @param float $points
     *
     * @return float
     */
    public function incrementScore(float $points): float
    {
        $this->score += $points;

        return $this->score;
    }

    public function getScore(): float
    {
        return $this->score;
    }
}