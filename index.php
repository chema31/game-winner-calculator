<?php
/**
 * Check all existing CSV files into "game_tables" directory and calculate the points with the aim of look for the winner.
 */
require __DIR__ . "/vendor/autoload.php";
include_once("config.php");

$tournament = \GWC\Models\Tournament::getInstance();
//Execute the current tournament calculations
$tournament->execute();