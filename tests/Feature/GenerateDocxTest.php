<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Result\GenerationResult;

class GenerateDocxTest extends TestCase
{
    public function test_can_generate_docx(): void
    {
        $template = __DIR__ . '/../Fixtures/templates/simple.docx';
        $output = __DIR__ . '/../Fixtures/output';

        $result = DocumentGenerator::make()
            ->template($template)
            ->values([
                'NAME' => 'John',
                'CITY' => 'Berlin',
            ])
            ->docx()
            ->saveTo($output)
            ->generate();

        $this->assertInstanceOf(
            GenerationResult::class,
            $result,
        );
    }
}