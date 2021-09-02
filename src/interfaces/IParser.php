<?php
/**
 * Interface with the aim parsing data from file
 */
namespace GWC\Interfaces;

interface IParser
{
    public function parseFile(string $gameFile);
}
