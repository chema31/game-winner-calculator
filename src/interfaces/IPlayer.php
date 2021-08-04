<?php

namespace GWC\Interfaces;

interface IPlayer
{
    public function getNick(): string;

    public function calculateScore(): float;
}
