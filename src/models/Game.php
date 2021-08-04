<?php
namespace GWC\Models;

use GWC\Interfaces\IGame;
use GWC\Interfaces\IPlayer;
use GWC\Interfaces\ITeam;

class Game implements IGame
{
    protected $id;
    protected $players = [];
    protected $winner = null;
    protected $teams = [];
    protected $winnerTeam = null;

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

        //Assign team score
        if( !array_key_exists($player->getTeamName(), $this->teams) ){
            $this->teams[$player->getTeamName()] = new Team($player->getTeamName());
        }
        $this->teams[$player->getTeamName()]->incrementScore($player->getKills());

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

    public function checkWinner()
    {
        $winnerTeam = $this->getWinnerTeam();

        foreach ($this->players as $player) {
            if($player->getTeamName() == $winnerTeam->getName()) {  //Player own to winner team
                $player->addExtraScore(10);
            }

            if(!$this->winner || $this->winner->getScore() < $player->getScore()){
                $this->winner = $player;
            }
        }

        return $this->winner;
    }

    /**
     * Check and return the winner team
     *
     * @return ITeam
     */
    protected function getWinnerTeam(): ITeam
    {
        foreach( $this->teams as $team){
            if(!$this->winnerTeam || $team->geScore() > $this->winnerTeam->getScore()){
                $this->winnerTeam = $team;
            }
        }

        return $this->winnerTeam;
    }

    /**
     * Return the current data of winnerTeam without re-calculate it
     *
     * @return ITeam
     */
    public function getCurrentWinnerTeam(): ITeam
    {
        return $this->winnerTeam;
    }
}