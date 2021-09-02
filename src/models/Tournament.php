<?php
/**
 * Game to be processed and scored in one occurrence.
 *
 * It's a Singleton implementation to ensure that only one Tournament is processed in each ejecution.
 */

namespace GWC\Models;

use GWC\Interfaces\IGame;

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
    public function execute()
    {
        $inputDir = scandir(INPUT_DIR);

        //Loop over all files
        foreach( $inputDir as $gameFile ){

            if( !in_array($gameFile, FILE_EXCEPTIONS) ){ //Skip navigation directories
                //Check if it"s a CSV file
                $extension = pathinfo($gameFile, PATHINFO_EXTENSION);
                if( $extension == "csv" ) {

                    $filePath = INPUT_DIR."/".$gameFile;

                    //Get CSV data
                    if (($fileHandler = fopen($filePath, "r")) !== false) {
                        $rowCounter = 0;
                        while (($rowData = fgetcsv($fileHandler, 1000, CSV_DELIMITER)) !== false) { //Loop over each csv row

                            if( 0 == $rowCounter ){ //First row: Game name
                                if( $this->exist($rowData[0]) ){
                                    echo "\nEL JUEGO ".$rowData[0]." YA HA SIDO VALORADO PREVIAMENTE EN EL TORNEO ACTUAL";
                                    break;  //Exit from while because this file doesn"t have to be processed

                                } else {
                                    //Create Game object and add it to the tournament
                                    $game = ($rowData[0] == "LEAGUE OF LEGENDS")? new \GWC\Models\LolGame($rowData[0]) : new \GWC\Models\Game($rowData[0]);
                                    $this->addGame($game);
                                }

                            } else {    //Other rows: Players
                                if( $game->getId() == "LEAGUE OF LEGENDS" ){    //LOL player
                                    if( $rowData && count($rowData) == 10 ){ //Exist necessry data
                                        if($rowData[1]){    //Nickname not empty
                                            if( !$game->exist($rowData[1]) ){
                                                $player = new \GWC\Models\LolPlayer(
                                                    $rowData[LolPlayer::$csvFields['name']],
                                                    $rowData[LolPlayer::$csvFields['nickName']],
                                                    $rowData[LolPlayer::$csvFields['teamName']],
                                                    $rowData[LolPlayer::$csvFields['kills']],
                                                    $rowData[LolPlayer::$csvFields['deaths']],
                                                    (strtolower($rowData[LolPlayer::$csvFields['winner']]) == 'true'),
                                                    $rowData[LolPlayer::$csvFields['position']],
                                                    $rowData[LolPlayer::$csvFields['assists']],
                                                    $rowData[LolPlayer::$csvFields['damage']],
                                                    $rowData[LolPlayer::$csvFields['heal']]
                                                );
                                                $game->addPlayer($player);

                                            } else {
                                                echo "\nYA HA SIDO PROCESADO PREVIAMENTE EL JUGADOR ".$rowData[1]." EN EL JUEGO ".$game->getId();
                                            }
                                        }

                                    } else {
                                        echo "\nDATOS INCORRECTOS EN LA FILA DE EXCEL: ".($rowCounter+1)." | JUEGO: ".$game->getId()." | NUM COLS: ".count($rowData);
                                    }

                                } else {    //Other game player
                                    if( $rowData && count($rowData) == 6 ){ //Exist necessry data
                                        if($rowData[1]){    //Nickname not empty
                                            if( !$game->exist($rowData[1]) ){
                                                $player = new \GWC\Models\Player(
                                                    Player::$csvFields['name'],
                                                    Player::$csvFields['nickName'],
                                                    Player::$csvFields['teamName'],
                                                    Player::$csvFields['kills'],
                                                    Player::$csvFields['deaths']
                                                );
                                                $game->addPlayer($player);

                                            } else {
                                                echo "\nYA HA SIDO PROCESADO PREVIAMENTE EL JUGADOR ".$rowData[1]." EN EL JUEGO ".$game->getId();
                                            }
                                        }

                                    } else {
                                        echo "\nDATOS INCORRECTOS EN LA FILA DE EXCEL: ".($rowCounter+1)." | JUEGO: ".$game->getId()." | NUM COLS: ".count($rowData);
                                    }
                                }
                            }

                            $rowCounter++;
                        }
                        fclose($fileHandler);
                    }

                } else {
                    echo "\nNO SE HA PODIDO PROCESAR EL ARCHIVO ".$gameFile." POR NO RESPETAR EL FORMATO CSV";
                }
            }
        }

//Show winners
        if( $this->games ){
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