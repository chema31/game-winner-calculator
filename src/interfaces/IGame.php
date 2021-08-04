<?php

namespace GWC\Interfaces;

interface IGame
{
    /**
     * Initialize the object
     *
     * @param string $id
     */
    public function __construct(string $id);

    /**
     * Get game id
     * @return string
     */
    public function getId(): string;
}
