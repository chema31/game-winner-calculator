<?php

namespace GWC\Interfaces;

interface ITeam
{
    public function __construct(string $name);

    public function getName(): string;

    public function incrementScore(float $points): float;

    public function getScore(): float;
}
