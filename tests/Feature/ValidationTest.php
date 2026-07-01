<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;

class ValidationTest extends TestCase
{
    public function test_requires_template(): void
    {
        $this->expectException(
            DocumentGeneratorException::class
        );

        $this->expectExceptionMessage(
            'Template is not specified.'
        );

        DocumentGenerator::make()
            ->docx()
            ->output(__DIR__ . '/../Fixtures/output')
            ->generate();
    }
}