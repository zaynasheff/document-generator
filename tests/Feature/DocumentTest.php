<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\DocumentFormat;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class DocumentTest extends TestCase
{
    public function test_can_set_template(): void
    {
        $document = new Document;

        $document->template('/templates/contract.docx');

        $this->assertSame(
            '/templates/contract.docx',
            $document->templatePath()
        );
    }

    public function test_can_set_values(): void
    {
        $document = new Document;

        $document->values([
            'FIRST_NAME' => 'Roman',
            'CITY' => 'Kazan',
        ]);

        $this->assertSame(
            [
                'FIRST_NAME' => 'Roman',
                'CITY' => 'Kazan',
            ],
            $document->placeholders()
        );
    }

    public function test_can_enable_docx_generation(): void
    {
        $document = new Document;

        $document->docx();

        $this->assertTrue(
            $document->hasFormat(DocumentFormat::DOCX)
        );
    }

    public function test_can_enable_pdf_generation(): void
    {
        $document = new Document;

        $document->pdf();

        $this->assertTrue(
            $document->hasFormat(DocumentFormat::PDF)
        );
    }

    public function test_does_not_duplicate_formats(): void
    {
        $document = new Document;

        $document
            ->pdf()
            ->pdf()
            ->pdf();

        $this->assertCount(
            1,
            $document->formats()
        );
    }

    public function test_document_has_one_copy_by_default(): void
    {
        $document = new Document;

        $this->assertSame(
            1,
            $document->copiesCount()
        );
    }

    public function test_can_set_document_copies(): void
    {
        $document = new Document;

        $document->copies(5);

        $this->assertSame(
            5,
            $document->copiesCount()
        );
    }

    public function test_document_copies_cannot_be_less_than_one(): void
    {
        $document = new Document;

        $document->copies(0);

        $this->assertSame(
            1,
            $document->copiesCount()
        );
    }
}
