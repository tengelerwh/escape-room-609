<?php

declare(strict_types=1);

namespace App\DomainModel\Game;

use App\DomainModel\ApiClient;
use App\DomainModel\Uuid;
use Psr\Log\LoggerInterface;

class GameService implements GameServiceInterface
{
    private ApiClient $apiClient;
    private GameRepositoryInterface $gameRepository;
    private LoggerInterface $logger;

    public function __construct(
        ApiClient $apiClient,
        GameRepositoryInterface $gameRepository,
        LoggerInterface $logger
    ) {
        $this->apiClient = $apiClient;
        $this->gameRepository = $gameRepository;
        $this->logger = $logger;
    }

    public function getCurrentGame(): Game
    {
        return Game::create(Uuid::create()->toString(), Uuid::create()->toString(), 'init');
    }

    public function getTimeLeft(Game $game): array
    {
        return $this->apiClient->getTimeLeft($game->getId());
    }
}
