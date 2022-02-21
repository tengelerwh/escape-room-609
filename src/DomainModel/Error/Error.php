<?php

declare(strict_types=1);

namespace App\DomainModel\Error;

class Error
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function toString(): string
    {
        return $this->message;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
