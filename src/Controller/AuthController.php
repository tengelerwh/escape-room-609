<?php

declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    /**
     * @Route(
     *     "/auth/login",
     *     name="auth.login",
     *     methods={"POST"}
     *     )
     */
    public function login(): JsonResponse
    {
        return new JsonResponse(
            [
                'token' => 'my_generated_token',
            ]
        );
    }

    /**
     * @Route(
     *     "/auth/login",
     *     name="auth.login.form",
     *     methods={"GET"}
     *     )
     */
    public function loginForm(): Response
    {
        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }
}
