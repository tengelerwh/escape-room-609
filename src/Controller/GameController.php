<?php

declare(strict_types = 1);

namespace App\Controller;

use App\DomainModel\Authentication\AuthenticationService;
use App\DomainModel\Game\GameService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(): Response
    {
        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    /**
     * @Route(
     *     "/game/list",
     *     name="game.list",
     *     methods={"GET"}
     *     )
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        if (false === $this->isValidRequest($request)) {
            return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Not logged in');
        }

        $client = $this->getClientFromRequest($request);

        return new JsonResponse(
            [
                'client' => $client,
                'games' => [
                     [
                        'uuid' => '5f8dc0a9-9fca-4a9f-a87c-27d07a667b0d',
                        'status' => 'ended',
                        'timeLeft' => 0,
                    ],
                    [
                        'uuid' => '188696ec-d58d-46dc-81a3-96b6d0495a6a',
                        'status' => 'waiting',
                        'timeLeft' => 3600,
                    ],
                    [
                        'uuid' => '5652a9c5-5ac2-4cc7-91a7-96231f50fbd1',
                        'status' => 'waiting',
                        'timeLeft' => 3600,
                    ],
                    [
                        'uuid' => '068c119-ea2a-4cf3-bf1c-762e0ac11d02',
                        'status' => 'ended',
                        'timeLeft' => 0,
                    ],
                    [
                        'uuid' => 'a4144737-0343-44ab-8d16-73d4a88b8b44',
                        'status' => 'pending',
                        'timeLeft' => 3000,
                    ],
                ],
            ]
        );
    }
}
