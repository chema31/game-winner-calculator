<?php
namespace GWC\Models;

class LolTeam extends Team
{
    protected $allPlayersWinners = true;

    public function isWinner(): bool
    {
        return $this->allPlayersWinners;
    }

    public function setLooser(): bool
    {
        $this->allPlayersWinners = false;

        return $this->allPlayersWinners;
    }
}