<?php
/**
 * Check all existing CSV files into "game_tables" directory and calculate the points with the aim of look for the winner.
 */
require __DIR__ . "/vendor/autoload.php";
include_once("config.php");

$inputDir = scandir(INPUT_DIR);

$tournament = \GWC\Models\Tournament::getInstance();

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
                        if( $tournament->exist($rowData[0]) ){
                            echo "\nEL JUEGO ".$rowData[0]." YA HA SIDO VALORADO PREVIAMENTE EN EL TORNEO ACTUAL";
                            break;  //Exit from while because this file doesn"t have to be processed

                        } else {
                            //Create Game object and add it to the tournament
                            $game = ($rowData[0] == "LEAGUE OF LEGENDS")? new \GWC\Models\LolGame($rowData[0]) : new \GWC\Models\Game($rowData[0]);
                            $tournament->addGame($game);
                        }

                    } else {    //Other rows: Players
                        if( $game->getId() == "LEAGUE OF LEGENDS" ){    //LOL player
                            if( $rowData && count($rowData) == 10 ){ //Exist necessry data
                                if($rowData[1]){    //Nickname not empty
                                    if( !$game->exist($rowData[1]) ){
                                        $player = new \GWC\Models\LolPlayer(
                                            $rowData[0],    //name
                                            $rowData[1],    //nick
                                            $rowData[2],    //team
                                            $rowData[5],    //kills
                                            $rowData[6],    //deaths
                                            (strtolower($rowData[3]) == 'true'),    //winner
                                            $rowData[4],    //position
                                            $rowData[7],    //assits
                                            $rowData[8],    //damage
                                            $rowData[9]     //heal
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
                                        $player = new \GWC\Models\Player($rowData[0],$rowData[1],$rowData[2],$rowData[4],$rowData[5]);
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