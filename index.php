<?php
/**
 * Check all existing CSV files into "game_tables" directory and calculate the points with the aim of look for the winner.
 */

include_once('config.php');

$inputDir = scandir(INPUT_DIR);

$tournament = \GWC\Models\Tournament::getInstance();

//Loop over all files
foreach( $inputDir as $gameFile ){

    if( !in_array($gameFile, FILE_EXCEPTIONS) ){ //Skip navigation directories
        //Check if it's a CSV file
        $extension = pathinfo($gameFile, PATHINFO_EXTENSION);
        if( $extension == 'csv' ) {

            $filePath = INPUT_DIR.'/'.$gameFile;

            //Get CSV data
            if (($fileHandler = fopen($filePath, "r")) !== false) {
                $rowCounter = 0;
                while (($rowData = fgetcsv($fileHandler, 1000, CSV_DELIMITER)) !== false) { //Loop over each csv row

                    if( 0 == $rowCounter ){ //First row: Game name
                        if( $tournament->exist($rowData[0]) ){
                            echo 'EL JUEGO '.$rowData[0].' YA HA SIDO VALORADO PREVIAMENTE EN EL TORNEO ACTUAL';
                            break;  //Exit from while because this file doesn't have to be processed

                        } else {
                            //Create Game object and add it to the tournament
                            $game = new \GWC\Models\Game($rowData[0]);
                            $tournament->addGame($game);
                        }

                    } else {    //Other rows: Players
                        if( $game->getId() == 'LEAGUE OF LEGENDS' ){    //LOL player
                            if( $rowData && count($rowData) == 10 ){ //Exist necessry data
                                if($rowData[1]){    //Nickname not empty
                                    if( !$game->exist($rowData[1]) ){
                                        $player = new \GWC\Models\LolPlayer(
                                            $rowData[0],    //name
                                            $rowData[1],    //nick
                                            $rowData[2],    //team
                                            $rowData[5],    //kills
                                            $rowData[6],    //deaths
                                            $rowData[3],    //winner
                                            $rowData[4],    //position
                                            $rowData[7],    //assits
                                            $rowData[8],    //damage
                                            $rowData[9]     //heal
                                        );
                                        $game->addPlayer($player);

                                    } else {
                                        echo 'YA HA SIDO PROCESADO PREVIAMENTE EL JUGADOR '.$rowData[1].' EN EL JUEGO '.$game->getId();
                                    }
                                }

                            } else {
                                echo 'FALTAN DATOS DE JUGADOR EN LA FILA: '.$rowCounter;
                            }

                        } else {    //Other game player
                            if( $rowData && count($rowData) == 6 ){ //Exist necessry data
                                if($rowData[1]){    //Nickname not empty
                                    if( !$game->exist($rowData[1]) ){
                                        $player = new \GWC\Models\Player($rowData[0],$rowData[1],$rowData[2],$rowData[4],$rowData[5]);
                                        $game->addPlayer($player);

                                    } else {
                                        echo 'YA HA SIDO PROCESADO PREVIAMENTE EL JUGADOR '.$rowData[1].' EN EL JUEGO '.$game->getId();
                                    }
                                }

                            } else {
                                echo 'FALTAN DATOS DE JUGADOR EN LA FILA: '.$rowCounter;
                            }
                        }
                    }

                    $rowCounter++;
                }
                fclose($fileHandler);
            }

        } else {
            echo 'NO SE HA PODIDO PROCESAR EL ARCHIVO '.$gameFile.' POR NO RESPETAR EL FORMATO CSV';
        }
    }
}

//Show winners
$winners = $tournament->getWinners();
if( $winners ){
    foreach( $winners as $game => $winner ){
        echo 'GAME: '.$game.' | PLAYER: '.$winner->getNick().' | SCORE: '.$winner->getScore();
    }
}