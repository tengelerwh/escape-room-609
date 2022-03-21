<?php

declare(strict_types=1);

namespace App\Controller;

use App\DomainModel\Authentication\AuthenticationService;
use App\DomainModel\Authentication\ClientAccessToken;
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
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    protected function renderLoginForm(string $redirect): Response
    {
        $error = '';
        $lastUserName = '';

        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
            'lastUsername' => $lastUserName,
            'redirect' => $redirect,
            'error' => $error,
        ]);
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

    protected function isValidRequest(Request $request): bool
    {
        $token = $this->getClientAccessTokenFromRequest($request);
        if (null === $token) {
            return false;
        }
        if ($this->authenticationService->hasValidAccessToken(
            $this->getRequestIdentificationData($request),
            $token
        )) {
            return true;
        }

        return false;
    }
}
