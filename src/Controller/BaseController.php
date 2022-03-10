<?php

declare(strict_types=1);

namespace App\Controller;

use App\DomainModel\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{

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
}
