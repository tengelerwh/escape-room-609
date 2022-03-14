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
     *     methods={"POST"}
     *     )
     */
    public function login(Request $request): Response
    {
        if ((false === $request->request->has('_username')) || (false === $request->request->has('_password'))) {
            throw new Exception('Invalid call to login');
        }
        $redirect = null;
        if (true === $request->request->has('_target_path')) {
            $redirect =  $request->request->get('_target_path');
            //@todo check for illegal path
        }
        $result = $this->authenticationService->login($request->request->get('_username'),$request->request->get('_password'));
        if ($result === null) {
            throw new Exception('Invalid username or password');
        }

        $response = new Response();
        $response->headers->add(['X-ACCESS-TOKEN', $result->toString()]);
        $response->isRedirect($redirect);
        $response->setStatusCode(RESPONSE::HTTP_FOUND);
        return $response;

//        return new JsonResponse(
//            [
//                'token' => $result->toString(),
//                'content' => implode(', ', $request->request->keys()),
//            ]
//        );
    }
}
