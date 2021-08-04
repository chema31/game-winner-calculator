<?php
namespace GWC\Models;


use GWC\Interfaces\IPlayer;

class Player implements IPlayer
{
    protected $name;
    protected $nickname; //Unique key
    protected $teamName;    //TODO: In future, we should decoupled as independient object
    protected $kills;
    protected $deaths;
    protected $score = 0;

    /**
     * Initialize the object
     *
     * @param string $id
     */
    public function __construct(string $name, string $nickname, string $teamName, int $kills, int $deaths){
        $this->name = $name;
        $this->nickname = $nickname;
        $this->teamName = $teamName;
        $this->kills = $kills;
        $this->deaths = $deaths;

        $this->calculateScore();
    }

    /**
     * Get player nickname
     * @return string
     */
    public function getNick(): string
    {
        return $this->nickname;
    }

    /**
     * Add a new player
     *
     * @param IPlayer $player
     *
     * @return $this
     */
    public function calculateScore(): float
    {
        $this->score = $this->kills/$this->deaths;

        return $this->score;
    }

    public function getScore(): float
    {
        return $this->score;
    }
}