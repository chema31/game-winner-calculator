<?php
/**
 * Check all existing CSV files into "game_tables" directory and calculate the points with the aim of look for the winner.
 */
require __DIR__ . "/vendor/autoload.php";
include_once("config.php");

$action = new \GWC\Actions\LookForWinners(new \GWC\Logic\CsvParser(), new \GWC\Views\CliResultsView());

//Execute the current tournament calculations
$action->execute();