<?php
/**
 * This class is an implementation of the view interface for rendering the results in CLI
 */

namespace GWC\Views;

use GWC\Interfaces\IView;
use GWC\Models\Tournament;

class CliResultsView implements IView
{
    public function render()
    {
        $tournament = Tournament::getInstance();

        //Show winners
        $games = $tournament->getGames();
        if( $games ){
            echo "\n\n\n\n**************************";
            echo "\n* AND THE WINNERS ARE...";
            echo "\n**************************";
            foreach( $games as $game){
                $winner = $game->checkWinner();
                $winnerTeam = $game->getCurrentWinnerTeam();
                echo "\n** GAME: ".$game->getId();
                if( $winnerTeam ){
                    echo "\n** GAME: ".$game->getId()." | TEAM WINNER: ".$winnerTeam->getName();
                }
                if( $winner ){
                    echo "\n** GAME: ".$game->getId()." | WINNER: ".$winner->getNick()." | SCORE: ".$winner->getScore();
                }
                echo "\n**************************";
            }
        }
    }
}