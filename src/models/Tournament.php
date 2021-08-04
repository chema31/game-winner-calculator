<?php
/**
 * Game to be processed and scored in one occurrence.
 *
 * It's a Singleton implementation to ensure that only one Tournament is processed in each ejecution.
 */

namespace GWC\Models;

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
     * @param IGame game
     * @return boolean
     */
    public function exist(IGame $game)
    {
        return array_key_exists($game->getId(), $this->games);
    }

    /**
     * Return an array with the tournament winners with an entry per game
     *
     * @return array
     */
    public function getWinners()
    {
        $winners = [];

        foreach( $this->games as $game){
            $winners[$game->getId()] = $game->getWinner();
        }

        return $winners;
    }
}