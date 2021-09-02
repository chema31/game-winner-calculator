<?php
/**
 * Game to be processed and scored in one occurrence.
 *
 * It's a Singleton implementation to ensure that only one Tournament is processed in each ejecution.
 */

namespace GWC\Models;

use GWC\Interfaces\IGame;
use GWC\Interfaces\IParser;

class Tournament
{
    private static $instance = null;

    protected $games = [];

    protected function __construct() { }
    protected function __clone() { }
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Get the unique instance of the tournament
     * @return Tournament
     */
    public static function getInstance(): Tournament
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add game to Tournament
     * @param IGame $team
     */
    public function addGame(IGame $game)
    {
        $this->games[$game->getId()] = $game;
    }

    /**
     * Check if game already exist into the tournament
     * @param string gameId
     * @return boolean
     */
    public function exist(string $gameId)
    {
        return array_key_exists($gameId, $this->games);
    }

    /**
     * Execute the tournament logic
     */
    public function execute(IParser $parser)
    {
        $inputDir = scandir(INPUT_DIR);

        //Loop over all files
        foreach( $inputDir as $gameFile ){

            if( !in_array($gameFile, FILE_EXCEPTIONS) ){ //Skip navigation directories
                $numParsedItems = $parser->parseFile($gameFile);
            }
        }

        //Show winners
        if( $numParsedItems && $this->games ){
            echo "\n\n\n\n**************************";
            echo "\n* AND THE WINNERS ARE...";
            echo "\n**************************";
            foreach( $this->games as $game){
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