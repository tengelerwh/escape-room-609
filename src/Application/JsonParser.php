<?php

declare(strict_types=1);

namespace App\Application;

use App\DomainModel\Error\Error;
use App\DomainModel\Error\ErrorList;
use JsonSchema\Validator;

class JsonParser
{
    private Validator $schemaValidator;
    private array $schemas;
    private ErrorList $errors;

    /**
     * @param Validator $schemaValidator
     * @param mixed[] $schemas
     */
    public function __construct(Validator $schemaValidator, array $schemas)
    {
        $this->schemaValidator = $schemaValidator;
        $this->schemas = $schemas;
        $this->errors = new ErrorList();
    }

    public function parseContent(string $route, string $rawContent): ?array
    {
        $content = json_decode($rawContent);
        if (null === $content) {
            $this->errors->add(new Error(json_last_error_msg()));
            return null;
        }
        $schema = $this->findSchemaForRoute($route);

        if (null === $schema) {
            return null;
        }
        $this->schemaValidator->reset();
        $this->errors->clear();

        $this->schemaValidator->validate($content, $schema);
        if (false === $this->schemaValidator->isValid()) {
            foreach($this->schemaValidator->getErrors() as $error) {
                $this->errors->add(new Error($error['message']));
            }
            return null;
        }

        // make sure we always return an array
        return json_decode($rawContent, true);
    }

    public function getErrors(): ErrorList
    {
        return $this->errors;
    }

    private function findSchemaForRoute(string $route): ?array
    {
        $parts = preg_split("/\./", $route);
        $schema = $this->schemas;
        foreach ($parts as $part) {
            if (false === array_key_exists($part, $schema)) {
                return null;
            }
            if (false === is_array($schema[$part])) {
                return null;
            }
            $schema = $schema[$part];
        }

        return $schema;
    }
}
