<?php
namespace GWC\Models;

use GWC\Interfaces\IGame;
use GWC\Interfaces\IPlayer;
use GWC\Interfaces\ITeam;

class LolGame extends Game
{
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
            $this->teams[$player->getTeamName()] = new LolTeam($player->getTeamName());
        }
        if( !$player->getWinner() ){
            $this->teams[$player->getTeamName()]->setLooser();
        }

        return $this->players[$player->getNick()];
    }

    /**
     * Check and return the winner team
     *
     * @return ITeam
     */
    protected function getWinnerTeam(): ITeam
    {
        foreach( $this->teams as $team){
            if($team->isWinner()){
                $this->winnerTeam = $team;
            }
        }

        return $this->winnerTeam;
    }
}