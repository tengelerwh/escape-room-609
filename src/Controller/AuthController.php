<?php

declare(strict_types = 1);

namespace App\Controller;

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
        $loginForm = $this->createForm('test');
        $loginForm->handleRequest($request);
        return new JsonResponse(
            [
                'token' => 'my_generated_token',
            ]
        );
    }
}
