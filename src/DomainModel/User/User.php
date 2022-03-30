<?php

declare(strict_types=1);

namespace App\DomainModel\User;

use JsonSerializable;

class User implements JsonSerializable
{
    private UserToken $userToken;

    private string $email;

    private string $password;

    private string $name;

    public static function create(string $email, string $password, string $name): static
    {
        return new static($email, $password, $name, UserToken::create());
    }

    private function __construct(string $email, string $password, string $name, UserToken $userToken)
    {
        $this->email = $email;
        $this->password = sha1($password);
        $this->name = $name;
        $this->userToken = $userToken;
    }

    public function getUserToken(): UserToken
    {
        return $this->userToken;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'token' => $this->userToken->toString(),
        ];
    }
}
