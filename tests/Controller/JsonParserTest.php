<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Application\JsonParser;
use JsonSchema\Validator;
use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    private JsonParser $parser;
    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new JsonParser(new Validator(), $this->getSchemas());
    }

    public function testParseContentWithUnknownSchemaReturnsNull(): void
    {
        $content = '';
        $this->assertNull($this->parser->parseContent('some.unknown', $content));
    }

    public function testParseContentWithEmptyContentReturnsNull(): void
    {
        $content = '';
        $this->assertNull($this->parser->parseContent('schema1.create', $content));
    }

    public function testParseContentOptionFieldNullValidates(): void
    {
        $content = '{"optionField":null}';
        $this->assertNotNull($this->parser->parseContent('schema1.create', $content));
    }

    public function testParseContentOptionFieldObjectValidates(): void
    {
        $content = '{"optionField":{"someSubField":10}}';
        $this->parser->parseContent('schema1.create', $content);
        $this->assertFalse($this->parser->getErrors()->hasErrors());
        $this->assertNotNull($this->parser->parseContent('schema1.create', $content));

    }

    public function testParseContentEnumFieldReturnsNullWithWrongEnum(): void
    {
        $content = '{"optionField":{"someSubField":10}, "enumField":"wrong"}';
        $this->assertNull($this->parser->parseContent('schema1.create', $content));
    }

    public function testParseContentEnumFieldValidatesEnum(): void
    {
        $content = '{"optionField":{"someSubField":10}, "enumField":"second"}';
        $this->assertNotNull($this->parser->parseContent('schema1.create', $content));
    }

    public function testParseContentGameAnswerValidates(): void
    {
        $content = '{"gameId": "b32be224-4ace-411a-90a4-59d96ed046d2", "puzzleId": "7b48740c-9616-421e-946d-ea28bd251262", "answer":"no"}';
        $this->assertNotNull($this->parser->parseContent('game.answer', $content));
    }

    private function getSchemas(): array
    {
        return [
            'schema1' => [
                'create' => [
                    'type' => 'object',
                    'properties' => [
                        'optionField' => [
                            'type' => ['object', 'null'],
                            'default' => null,
                        ],
                        'enumField' => [
                            'type' => 'string',
                            'enum' => ['first', 'second'],
                        ],
                    ],
                ],
            ],
            'game' => [
                'answer' => [
                    'type' => 'object',
                    'required' => [
                        'gameId',
                        'puzzleId',
                    ],
                    'properties' => [
                        'gameId' => [
                            'type' => 'string',
                            'pattern' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}',
                        ],
                        'puzzleId' => [
                            'type' => 'string',
                            'pattern' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}',
                        ],
                        'answer' => [
                            'type' => 'string',
                            'enum' => ['yes', 'no'],
                        ],
                    ],
                ],
                'end' => [
                    'type' => 'object',
                    'properties' => [
                        'field1' => [
                            'type' => 'boolean',
                        ],
                        'field2' => [
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
        ];
    }
}
