<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use Zaynasheff\DocumentGenerator\Converters\PdfConverter;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Mergers\PdfMerger;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class PdfMergerTest extends TestCase
{
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function test_can_merge_pdf_documents(): void
    {
        if (! app(PdfConverter::class)->isAvailable()) {
            $this->markTestSkipped(
                'LibreOffice is not available.'
            );
        }

        $output = __DIR__.'/../Fixtures/output';

        $first = DocumentGenerator::make()
            ->template(
                __DIR__.'/../Fixtures/templates/simple.docx'
            )
            ->values([
                'FIRST_NAME' => 'John',
                'LAST_NAME' => 'Anderson',
                'CITY' => 'Berlin',
            ])
            ->name('contract')
            ->pdf()
            ->output($output)
            ->generate();

        $second = DocumentGenerator::make()
            ->template(
                __DIR__.'/../Fixtures/templates/simple.docx'
            )
            ->values([
                'FIRST_NAME' => 'Mike',
                'LAST_NAME' => 'Smith',
                'CITY' => 'London',
            ])
            ->name('agreement')
            ->pdf()
            ->output($output)
            ->generate();

        $this->assertNotNull(
            $first->pdfPath()
        );

        $this->assertNotNull(
            $second->pdfPath()
        );

        $destination = $output
            .DIRECTORY_SEPARATOR
            .'package.pdf';

        $merger = new PdfMerger;

        $result = $merger->merge(
            [
                $first->pdfPath(),
                $second->pdfPath(),
            ],
            $destination
        );

        $this->assertSame(
            $destination,
            $result
        );

        $this->assertFileExists(
            $destination
        );
    }
}
