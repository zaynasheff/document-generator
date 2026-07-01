<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PHPUnit\Framework\TestCase;
use Zaynasheff\DocumentGenerator\Configuration\DocumentGeneratorConfig;
use Zaynasheff\DocumentGenerator\Converters\PdfConverter;
use Zaynasheff\DocumentGenerator\DocumentGenerator;

class GeneratePdfTest extends TestCase
{
    private const LIBRE_OFFICE_COMMAND =
        '/Applications/LibreOffice.app/Contents/MacOS/soffice';

    public function test_can_generate_pdf(): void
    {
        $pdfConverter = new PdfConverter(
            new DocumentGeneratorConfig(
                self::LIBRE_OFFICE_COMMAND
            )
        );

        if (! $pdfConverter->isAvailable()) {
            $this->markTestSkipped(
                'LibreOffice is not available.'
            );
        }

        $template = __DIR__ . '/../Fixtures/templates/simple.docx';
        $output = __DIR__ . '/../Fixtures/output';

        $result = DocumentGenerator::make()
            ->template($template)
            ->values([
                'FIRST_NAME' => 'John',
                'LAST_NAME'  => 'Anderson',
                'CITY'       => 'Berlin',
            ])
            ->pdf()
            ->output($output)
            ->generate();

        $this->assertTrue(
            $result->hasPdf()
        );

        $this->assertFileExists(
            $result->pdfPath()
        );
    }
}