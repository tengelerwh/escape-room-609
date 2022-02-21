<?php

declare(strict_types=1);

namespace App\DomainModel\Error;

class ErrorList
{
    /** @var Error[]  */
    private array $list;

    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->list = [];
    }

    public function add(Error $error): void
    {
        $this->list[] = $error;
    }

    public function hasErrors(): bool
    {
        return count($this->list) > 0;
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->list;
    }

    public function clear(): void
    {
        $this->list = [];
    }

    public function toString(): string
    {
        $result = '';
        foreach($this->list as $index => $error) {
            $result .= sprintf("%s%d: %s", PHP_EOL, $index, $error);
        }
        return $result;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
