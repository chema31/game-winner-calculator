<?php
namespace GWC\Models;

use GWC\Interfaces\IGame;
use GWC\Interfaces\IPlayer;

class Game implements IGame
{
    protected $id;
    protected $players = [];
    protected $winner = null;
    protected $teams = [];

    /**
     * Initialize the object
     *
     * @param string $id
     */
    public function __construct(string $id){
        $this->id = $id;
    }

    /**
     * Get game id
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Add a new player
     *
     * @param IPlayer $player
     *
     * @return $this
     */
    public function addPlayer(IPlayer $player): IPlayer
    {
        $this->players[$player->getNick()] = $player;

        //TODO: Recalculate team score in game

        //Check: Winner
        if( !$this->winner || $player->getScore() > $this->winner->getScore() ){
            $this->winner = $player;
        }

        return $this->players[$player->getNick()];
    }

    /**
     * Check if player exist
     *
     * @param string $nick
     * @return bool
     */
    public function exist(string $nick): bool
    {
        return array_key_exists($nick, $this->players);
    }

    public function getWinner()
    {
        return $this->winner;
    }
}