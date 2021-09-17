<?php
namespace GWC\Models;

class LolPlayer extends Player
{
    protected $winner;
    protected $position;
    protected $assists;
    protected $damage;
    protected $heal;
    public static $csvFields = [
        'name' => 0,
        'nickName' => 1,
        'teamName' => 2,
        'kills' => 5,
        'deaths' => 6,
        'winner' => 3,
        'position' => 4,
        'assists' => 7,
        'damage' => 8,
        'heal' => 9
    ];

    /**
     * Initialize the object
     *
     * @param string $id
     */
    public function __construct(string $name, string $nickname, string $teamName, int $kills, int $deaths, bool $winner, string $position, int $assists, int $damage, int $heal)
    {

        parent::__construct($name,$nickname,$teamName,$kills,$deaths);

        $this->winner = $winner;
        $this->position = $position;
        $this->assists = $assists;
        $this->damage = $damage;
        $this->heal = $heal;

        $this->calculateScore();
    }

    /**
     * Calculate the score of current player
     *
     * @return $this
     */
    public function calculateScore(): float
    {
        $kda = ($this->kills + $this->assists)/$this->deaths;
        if ($this->position == 'S') {
            $this->score = ($this->damage * 0.01) + ($this->heal * 0.03) + $kda;

        } elseif( $this->position == 'J' ) {
            $this->score = ($this->damage * 0.02) + ($this->heal * 0.02) + $kda;

        } else {  //I guess the positions are limited
            $this->score = ($this->damage * 0.03) + ($this->heal * 0.01) + $kda;
        }

        return $this->score;
    }

    public function getWinner(): bool
    {
        return $this->winner;
    }

}