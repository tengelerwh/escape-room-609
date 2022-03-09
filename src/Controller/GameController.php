<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Application\JsonParser;
use App\DomainModel\Game\GameService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private GameService $gameService;
    private LoggerInterface $logger;

    public function __construct(
        GameService $gameService,
        LoggerInterface $logger
    ) {
        $this->gameService = $gameService;
        $this->logger = $logger;
    }

    /**
     * @Route(
     *     "/",
     *     name="home",
     *     methods={"GET"}
     *     )
     * @return Response
     */
    public function index(): Response
    {
        $game = $this->gameService->getCurrentGame();
        $timeLeft = $this->gameService->getTimeLeft($game);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
}
