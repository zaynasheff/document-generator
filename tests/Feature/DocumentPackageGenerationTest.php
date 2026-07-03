<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\Converters\PdfConverter;
use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class DocumentPackageGenerationTest extends TestCase
{
    public function test_can_generate_single_document_package(): void
    {
        if (! app(PdfConverter::class)->isAvailable()) {
            $this->markTestSkipped(
                'LibreOffice is not available.'
            );
        }

        $package = DocumentPackage::make();

        $package
            ->output(
                __DIR__.'/../Fixtures/output'
            )
            ->addDocument()
            ->template(
                __DIR__.'/../Fixtures/templates/simple.docx'
            )
            ->values([
                'FIRST_NAME' => 'John',
                'LAST_NAME' => 'Anderson',
                'CITY' => 'Berlin',
            ])
            ->pdf();

        $result = $package->generate();

        $this->assertSame(
            1,
            $result->count()
        );

        $generation = $result->first();

        $this->assertNotNull(
            $generation
        );

        $this->assertTrue(
            $generation->hasPdf()
        );

        $pdfPath = $generation->pdfPath();

        $this->assertNotNull(
            $pdfPath
        );

        $this->assertFileExists(
            $pdfPath
        );
    }

    public function test_can_generate_multiple_documents(): void
    {
        if (! app(PdfConverter::class)->isAvailable()) {
            $this->markTestSkipped(
                'LibreOffice is not available.'
            );
        }

        $package = DocumentPackage::make();

        $package->output(
            __DIR__.'/../Fixtures/output'
        );

        $package
            ->addDocument()
            ->template(
                __DIR__.'/../Fixtures/templates/simple.docx'
            )
            ->values([
                'FIRST_NAME' => 'John',
                'LAST_NAME' => 'Anderson',
                'CITY' => 'Berlin',
            ])
            ->name('contract')
            ->pdf();

        $package
            ->addDocument()
            ->template(
                __DIR__.'/../Fixtures/templates/simple.docx'
            )
            ->values([
                'FIRST_NAME' => 'Mike',
                'LAST_NAME' => 'Smith',
                'CITY' => 'London',
            ])
            ->name('agreement')
            ->pdf();

        $result = $package->generate();

        $this->assertSame(
            2,
            $result->count()
        );

        $first = $result->results()[0];
        $second = $result->results()[1];

        $this->assertTrue(
            $first->hasPdf()
        );

        $this->assertTrue(
            $second->hasPdf()
        );

        $this->assertNotNull(
            $first->pdfPath()
        );

        $this->assertNotNull(
            $second->pdfPath()
        );

        $this->assertFileExists(
            $first->pdfPath()
        );

        $this->assertFileExists(
            $second->pdfPath()
        );

        $this->assertSame(
            __DIR__.'/../Fixtures/output/contract.pdf',
            $first->pdfPath()
        );

        $this->assertSame(
            __DIR__.'/../Fixtures/output/agreement.pdf',
            $second->pdfPath()
        );
    }
}
