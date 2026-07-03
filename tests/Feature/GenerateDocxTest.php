<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Tests\TestCase;
use ZipArchive;

class GenerateDocxTest extends TestCase
{
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_can_generate_docx(): void
    {
        $result = DocumentGenerator::make()
            ->template($this->templatePath())
            ->values($this->values())
            ->docx()
            ->output($this->outputDirectory())
            ->generate();

        $this->assertTrue(
            $result->hasDocx()
        );

        $docxPath = $result->docxPath();

        $this->assertNotNull(
            $docxPath
        );

        $this->assertFileExists(
            $docxPath
        );

        $xml = $this->getDocumentXml(
            $docxPath
        );

        $this->assertStringContainsString(
            'John',
            $xml
        );

        $this->assertStringContainsString(
            'Anderson',
            $xml
        );

        $this->assertStringContainsString(
            'Berlin',
            $xml
        );
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_can_generate_docx_with_custom_name(): void
    {
        $result = DocumentGenerator::make()
            ->template($this->templatePath())
            ->values($this->values())
            ->name('contract_001')
            ->docx()
            ->output($this->outputDirectory())
            ->generate();

        $this->assertTrue(
            $result->hasDocx()
        );

        $docxPath = $result->docxPath();

        $this->assertNotNull(
            $docxPath
        );

        $this->assertSame(
            $this->outputDirectory().DIRECTORY_SEPARATOR.'contract_001.docx',
            $docxPath
        );

        $this->assertFileExists(
            $docxPath
        );
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_name_is_normalized(): void
    {
        $result = DocumentGenerator::make()
            ->template($this->templatePath())
            ->values($this->values())
            ->name('contract_001.docx')
            ->docx()
            ->output($this->outputDirectory())
            ->generate();

        $this->assertSame(
            $this->outputDirectory().DIRECTORY_SEPARATOR.'contract_001.docx',
            $result->docxPath()
        );
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

    private function getDocumentXml(
        string $path
    ): string {
        $zip = new ZipArchive;

        $this->assertTrue(
            $zip->open($path)
        );

        $xml = $zip->getFromName(
            'word/document.xml'
        );

        $zip->close();

        $this->assertNotFalse(
            $xml,
            'word/document.xml not found.'
        );

        return $xml;
    }
}
