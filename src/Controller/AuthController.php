<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'auth')]
    public function login(): JsonResponse
    {
        return new JsonResponse(
            [
                'token' => 'my_generated_token',
            ]
        );
    }
}
