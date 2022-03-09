<?php

declare(strict_types=1);

namespace App\DomainModel\Game;

interface GameServiceInterface
{
    public function getCurrentGame(): Game;
    public function getTimeLeft(Game $game): array;
}
