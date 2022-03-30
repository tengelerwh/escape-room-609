<?php

declare(strict_types=1);

namespace App\Controller;

use App\DomainModel\Authentication\RefreshToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    /**
     * @Route(
     *     "/auth/login",
     *     name="auth.login",
     *     methods={"POST"}
     *     )
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $content = $this->parseRequestContent($request);
        } catch (InvalidContentException $exception) {
            return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid call to login');
        }

        if ((false === array_key_exists('_username', $content)) || (false === array_key_exists('_password', $content))) {
            return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid call to login (missing data)');
        }

        $email = $content['_username'];
        $password = $content['_password'];
        $client = $this->authenticationService->login($email, $password);
        if (null === $client) {
            $message = 'Invalid username or password';
            return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, $message);
        }

        $this->authenticationService->persistClient($client);

        return new JsonResponse(
            [
                'refresh' => $client->getRefreshToken()->toString(),
                'token' => $client->getAccessToken()->toString(),
                'loggedIn' => true,
                'name' => $client->getUser()->getName(),
            ]
        );
    }

    /**
     * @Route(
     *     "/auth/refresh",
     *     name="auth.refresh",
     *     methods={"POST"}
     *     )
     *
     * Generate a new access token for the client. Called when already loggedIn but reload of page is triggered
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            $content = $this->parseRequestContent($request);
        } catch (InvalidContentException $exception) {
            return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid call to refresh');
        }

        if (false === array_key_exists('refresh', $content)) {
            return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid call to refresh (missing data)');
        }
        $refreshToken = RefreshToken::fromString($content['refresh']);
        $client = $this->authenticationService->refreshClient($refreshToken);
        if (null === $client) {
            return new JsonResponse(
                [
                    'refresh' => null,
                    'token' => null,
                    'loggedIn' => false,
                    'name' => '',
                ]
            );        }

        return new JsonResponse(
            [
                'refresh' => $client->getRefreshToken()->toString(),
                'token' => $client->getAccessToken()->toString(),
                'loggedIn' => true,
                'name' => $client->getUser()->getName(),
            ]
        );
    }
}
