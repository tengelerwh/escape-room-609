<?php

namespace App\Controller;

use App\Application\JsonParser;
use App\DomainModel\Error\ErrorList;
use App\DomainModel\EscapeRoom\EscapeRoomService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    private EscapeRoomService $escapeRoomService;
    private JsonParser $jsonParser;
    private LoggerInterface $logger;

    public function __construct(
        EscapeRoomService $escapeRoomDatabaseRepository,
        JsonParser $jsonParser,
        LoggerInterface $logger
    )
    {
        $this->escapeRoomService = $escapeRoomDatabaseRepository;
        $this->jsonParser = $jsonParser;
        $this->logger = $logger;
    }

    /**
     * @Route(
     *     "/api/v1/room/create",
     *     name="room.create",
     *     methods={"POST"}
     *     )
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $content = $this->jsonParser->parseContent('room.create', $request->getContent());
            if (null === $content) {
                $this->logger->info(sprintf('Create failed: %s', $this->jsonParser->getErrors()));
                throw new InvalidContentException(sprintf('Cannot create room with data %s', $request->getContent()));
            }

            $escapeRoom = $this->escapeRoomService->createRoom($content['name'], $content['description']);

            return new JsonResponse(
                [
                    'room' => $escapeRoom->getId(),
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return $this->showException($exception);
        }
    }

    /**
     * @Route("/api/v1/room/{roomId}/show",
     *     name="room.show",
     *     methods={"GET"},
     *     requirements={"roomId"="%match.uuid%"}
     *     )
     * @param string $roomId
     * @return JsonResponse
     */
    public function show(string $roomId): JsonResponse
    {
        try {
            $escapeRoom = $this->escapeRoomService->getRoom($roomId);
            if (null === $escapeRoom) {
                return $this->showErrors($this->escapeRoomService->getErrors());
            }

            return new JsonResponse(
                [
                    'room' => [
                        'uuid' => $escapeRoom->getId(),
                        'name' => $escapeRoom->getName(),
                        'description' => $escapeRoom->getDescription(),
                        'played' => 0,
                    ],
                ]
            );
        } catch (Exception $exception) {
            return $this->showException($exception);
        }
    }

    private function showException(Exception $exception): JsonResponse
    {
        return new JsonResponse(
            [
                'Exception' => $exception->getMessage(),
                'stack' => $exception->getTraceAsString(),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    private function showErrors(ErrorList $errors): JsonResponse
    {
        $content = [
            'errors' => [],
        ];
        foreach ($errors->getErrors() as $error) {
            $content['errors'][] = $error->getMessage();
        }

        return new JsonResponse(
            $content,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
