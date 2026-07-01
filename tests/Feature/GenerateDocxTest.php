<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PHPUnit\Framework\TestCase;
use ZipArchive;
use Zaynasheff\DocumentGenerator\DocumentGenerator;

class GenerateDocxTest extends TestCase
{
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_can_generate_docx(): void
    {
        $template = __DIR__ . '/../Fixtures/templates/simple.docx';
        $output = __DIR__ . '/../Fixtures/output';

        if (! is_dir($output)) {
            mkdir($output, 0777, true);
        }

        $result = DocumentGenerator::make()
            ->template($template)
            ->values([
                'FIRST_NAME' => 'John',
                'LAST_NAME'  => 'Anderson',
                'CITY'       => 'Berlin',
            ])
            ->docx()
            ->output($output)
            ->generate();

        $this->assertTrue(
            $result->hasDocx()
        );

        $this->assertFileExists(
            $result->docxPath()
        );

        $xml = $this->getDocumentXml(
            $result->docxPath()
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

    private function getDocumentXml(
        string $path
    ): string {

        $zip = new ZipArchive();

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