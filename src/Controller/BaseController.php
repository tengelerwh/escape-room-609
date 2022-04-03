<?php

declare(strict_types=1);

namespace App\Controller;

use App\DomainModel\Authentication\AuthenticationService;
use App\DomainModel\Authentication\ClientAccessToken;
use App\DomainModel\Authentication\GameClient;
use App\DomainModel\Uuid;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    protected AuthenticationService $authenticationService;

    protected LoggerInterface $logger;

    public function __construct(
        AuthenticationService $authenticationService,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->authenticationService = $authenticationService;
    }

    protected function returnJsonErrorResponse(int $status, string $message): JsonResponse
    {
        $data = [
            'message' => $message,
        ];
        $this->logger->error(sprintf('Unauthorized (401): %s', $message), ['app']);
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @return array
     * @throws InvalidContentException
     */
    protected function parseRequestContent(Request $request): array
    {
        $body = json_decode($request->getContent(), true);
        if (false === $body) {
            throw new InvalidContentException('Invalid data');
        }
        return $body;
    }

    protected function renderWithHeaders(string $view, array $parameters = [], array $headers = []): Response
    {
        $headers = array_merge(['X-CLIENT-TOKEN' => Uuid::create()->toString()], $headers);
        $response = new Response(null, Response::HTTP_OK, $headers);
        return $this->render($view, $parameters, $response);
    }

    protected function getRequestIdentificationData(Request $request): array
    {
        $data = [
            'ip' => $request->getClientIp(),
            'language' => $request->getPreferredLanguage(),
            'userInfo' => $request->getUserInfo(),
            'userAgent' => $request->headers->get('user-agent'),
            'contentType' => $request->headers->get('content-type'),
        ];
        return $data;
    }

    protected function getClientAccessTokenFromRequest(Request $request): ?ClientAccessToken
    {
        if (false === $request->headers->has('X-ACCESS-TOKEN')) {
            return null;
        }
        return ClientAccessToken::fromString($request->headers->get('X-ACCESS-TOKEN'));
    }

    protected function getClientFromRequest(Request $request): ?GameClient
    {
        $accessToken = $this->getClientAccessTokenFromRequest($request);
        if (null === $accessToken) {
            return null;
        }
        $client = $this->authenticationService->getClientByAccessToken($accessToken, $this->getRequestIdentificationData($request));
        if ($client->isExpired()) {
            return null;
        }
        return $client;
    }

    protected function isValidRequest(Request $request): bool
    {
        $accessToken = $this->getClientAccessTokenFromRequest($request);
        if (null === $accessToken) {
            return false;
        }
        if (false === $this->authenticationService->isValidAccessToken($accessToken)) {
            return false;
        }

        return true;
    }
}
