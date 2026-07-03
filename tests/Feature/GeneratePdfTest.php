<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use Zaynasheff\DocumentGenerator\Converters\PdfConverter;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class GeneratePdfTest extends TestCase
{
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_can_generate_pdf(): void
    {
        $this->skipIfLibreOfficeIsUnavailable();

        $result = DocumentGenerator::make()
            ->template($this->templatePath())
            ->values($this->values())
            ->pdf()
            ->output($this->outputDirectory())
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

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_can_generate_pdf_with_custom_name(): void
    {
        $this->skipIfLibreOfficeIsUnavailable();

        $result = DocumentGenerator::make()
            ->template($this->templatePath())
            ->values($this->values())
            ->name('contract_001')
            ->pdf()
            ->output($this->outputDirectory())
            ->generate();

        $this->assertTrue(
            $result->hasPdf()
        );

        $pdfPath = $result->pdfPath();

        $this->assertNotNull(
            $pdfPath
        );

        $this->assertSame(
            $this->outputDirectory().DIRECTORY_SEPARATOR.'contract_001.pdf',
            $pdfPath
        );

        $this->assertFileExists(
            $pdfPath
        );
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_pdf_name_is_normalized(): void
    {
        $this->skipIfLibreOfficeIsUnavailable();

        $result = DocumentGenerator::make()
            ->template($this->templatePath())
            ->values($this->values())
            ->name('contract_001.pdf')
            ->pdf()
            ->output($this->outputDirectory())
            ->generate();

        $this->assertSame(
            $this->outputDirectory().DIRECTORY_SEPARATOR.'contract_001.pdf',
            $result->pdfPath()
        );
    }

    private function skipIfLibreOfficeIsUnavailable(): void
    {
        if (! app(PdfConverter::class)->isAvailable()) {
            $this->markTestSkipped(
                'LibreOffice is not available.'
            );
        }
    }

    private function templatePath(): string
    {
        return __DIR__.'/../Fixtures/templates/simple.docx';
    }

    /**
     * @return array<string, string>
     */
    private function values(): array
    {
        return [
            'FIRST_NAME' => 'John',
            'LAST_NAME' => 'Anderson',
            'CITY' => 'Berlin',
        ];
    }

    private function outputDirectory(): string
    {
        $directory = __DIR__.'/../Fixtures/output';

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return $directory;
    }
}
