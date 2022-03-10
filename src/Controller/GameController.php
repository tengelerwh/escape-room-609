<?php

declare(strict_types = 1);

namespace App\Controller;

use App\DomainModel\Authentication\AuthenticationService;
use App\DomainModel\Game\GameService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private GameService $gameService;
    private LoggerInterface $logger;
    private AuthenticationService $authenticationService;

    public function __construct(
        GameService $gameService,
        AuthenticationService $authenticationService,
        LoggerInterface $logger
    ) {
        $this->gameService = $gameService;
        $this->logger = $logger;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @Route(
     *     "/",
     *     name="home",
     *     methods={"GET"}
     *     )
     * @return Response
     */
    public function index(Request $request): Response
    {
        $clientAccessToken = $this->authenticationService->getClientAccessTokenFromRequest($request);
        if (false === $this->authenticationService->isloggedIn($clientAccessToken)) {
            return $this->redirect($this->generateUrl('auth.login.form'));
        }

        $game = $this->gameService->getCurrentGame();
        $timeLeft = $this->gameService->getTimeLeft($game);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
}
