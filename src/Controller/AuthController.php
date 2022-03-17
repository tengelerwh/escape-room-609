<?php

declare(strict_types = 1);

namespace App\Controller;

use App\DomainModel\Authentication\AuthenticationService;
use App\DomainModel\Game\GameService;
use Exception;
use Psr\Log\LoggerInterface;
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
     *     methods={"POST", "GET"}
     *     )
     */
    public function login(Request $request): JsonResponse
    {
        if ('POST' === $request->getMethod()) {
            $body = json_decode($request->getContent(), true);
            if (false === $body) {
                return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid call to login');
            }
            if ((false === array_key_exists('_username', $body)) || (false ===array_key_exists('_password', $body))) {
                return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, 'Invalid call to login (missing data)');
            }
            $redirect = null;
            if (true === $request->request->has('_target_path')) {
                $redirect =  $request->request->get('_target_path');
                //@todo check for illegal path
            }

            $email = $body['_username'];
            $password = $body['_password'];
            $token = $this->authenticationService->login($email, $password);
            if (null === $token) {
                $message = 'Invalid username or password';
                return $this->returnJsonErrorResponse(Response::HTTP_UNAUTHORIZED, $message);
            }
        } else {
            $email = 'some known user';
            $token = $this->authenticationService->getStoredClientAccessToken();
        }

        return new JsonResponse(
            [
                'token' => (null !== $token) ? $token->toString() : null,
                'loggedIn' => (null !== $token),
                'name' => $email,
            ]
        );
    }
}
