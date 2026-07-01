<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PHPUnit\Framework\TestCase;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;

class ValidationTest extends TestCase
{
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
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

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_requires_existing_template(): void
    {
        $this->expectException(
            DocumentGeneratorException::class
        );

        $this->expectExceptionMessage(
            'Template file does not exist.'
        );

        DocumentGenerator::make()
            ->template(__DIR__ . '/../Fixtures/templates/not-found.docx')
            ->docx()
            ->output(__DIR__ . '/../Fixtures/output')
            ->generate();
    }
}