<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\Converters\PdfConverter;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class GeneratePdfTest extends TestCase
{
    public function test_can_generate_pdf(): void
    {
        $pdfConverter = app(PdfConverter::class);

        if (! $pdfConverter->isAvailable()) {
            $this->markTestSkipped(
                'LibreOffice is not available.'
            );
        }

        $template = __DIR__.'/../Fixtures/templates/simple.docx';
        $output = __DIR__.'/../Fixtures/output';

        $result = DocumentGenerator::make()
            ->template($template)
            ->values([
                'FIRST_NAME' => 'John',
                'LAST_NAME' => 'Anderson',
                'CITY' => 'Berlin',
            ])
            ->pdf()
            ->output($output)
            ->generate();

        $this->assertTrue(
            $result->hasPdf()
        );

        $pdfPath = $result->pdfPath();

        $this->assertNotNull(
            $pdfPath
        );

        $this->assertFileExists(
            $pdfPath
        );
    }
}
