<?php
/**
 * Class with logic to populate a tournament by parsing a CSV file
 */

namespace GWC\Logic;

use GWC\Interfaces\IParser;
use GWC\Models\LolPlayer;
use GWC\Models\Player;
use GWC\Models\Tournament;

class CsvParser implements IParser
{
    public function parseFile(string $gameFile)
    {
        $tournament = Tournament::getInstance();
        $rowCounter = 0;

        //Check if it"s a CSV file
        $extension = pathinfo($gameFile, PATHINFO_EXTENSION);
        if( $extension == "csv" ) {

            $filePath = INPUT_DIR."/".$gameFile;

            //Get CSV data
            if (($fileHandler = fopen($filePath, "r")) !== false) {

                while (($rowData = fgetcsv($fileHandler, CSV_MAX_LENGTH, CSV_DELIMITER))) { //Loop over each csv row

                    if( ROW_GAME_NAME == $rowCounter ){ //First row: Game name
                        if( $tournament->exist($rowData[COL_GAME_NAME]) ){
                            echo "\nEL JUEGO ".$rowData[COL_GAME_NAME]." YA HA SIDO VALORADO PREVIAMENTE EN EL TORNEO ACTUAL";
                            break;  //Exit from while because this file doesn"t have to be processed

                        } else {
                            //Create Game object and add it to the tournament
                            $game = ($rowData[COL_GAME_NAME] == "LEAGUE OF LEGENDS")? new \GWC\Models\LolGame($rowData[COL_GAME_NAME]) : new \GWC\Models\Game($rowData[COL_GAME_NAME]);
                            $tournament->addGame($game);
                        }

                    } else {    //Other rows: Players
                        if( $game->getId() == "LEAGUE OF LEGENDS" ){    //LOL player
                            if( $rowData && count($rowData) == NUM_COLS_LOL ){ //Exist necessry data
                                if($rowData[LolPlayer::$csvFields['nickName']]){    //Nickname not empty
                                    if( !$game->exist($rowData[LolPlayer::$csvFields['nickName']]) ){
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
                                        echo "\nYA HA SIDO PROCESADO PREVIAMENTE EL JUGADOR ".$rowData[LolPlayer::$csvFields['nickName']]." EN EL JUEGO ".$game->getId();
                                    }
                                }

                            } else {
                                echo "\nDATOS INCORRECTOS EN LA FILA DE EXCEL: ".($rowCounter+1)." | JUEGO: ".$game->getId()." | NUM COLS: ".count($rowData);
                            }

                        } else {    //Other game player
                            if( $rowData && count($rowData) == NUM_COLS_GAME ){ //Exist necessry data
                                if($rowData[Player::$csvFields['nickName']]){    //Nickname not empty
                                    if( !$game->exist($rowData[Player::$csvFields['nickName']]) ){
                                        $player = new \GWC\Models\Player(
                                            $rowData[Player::$csvFields['name']],
                                            $rowData[Player::$csvFields['nickName']],
                                            $rowData[Player::$csvFields['teamName']],
                                            $rowData[Player::$csvFields['kills']],
                                            $rowData[Player::$csvFields['deaths']]
                                        );
                                        $game->addPlayer($player);

                                    } else {
                                        echo "\nYA HA SIDO PROCESADO PREVIAMENTE EL JUGADOR ".$rowData[Player::$csvFields['nickName']]." EN EL JUEGO ".$game->getId();
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

        return $rowCounter;
    }
}