<?php

declare(strict_types = 1);

namespace App\Controller;

use App\DomainModel\Authentication\AuthenticationService;
use App\DomainModel\Game\GameService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends BaseController
{
    private GameService $gameService;

    public function __construct(
        GameService $gameService,
        AuthenticationService $authenticationService,
        LoggerInterface $logger
    ) {
        parent::__construct($authenticationService, $logger);
        $this->gameService = $gameService;
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
        $currentUrl = $this->generateUrl('home', $request->query->all());
//        $clientAccessToken = $this->authenticationService->getClientAccessTokenFromRequest($request);
//        if (false === $this->authenticationService->isloggedIn()) {
//            return $this->renderLoginForm($currentUrl);
//        }
//
//        $game = $this->gameService->getCurrentGame();
//        $timeLeft = $this->gameService->getTimeLeft($game);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
}
