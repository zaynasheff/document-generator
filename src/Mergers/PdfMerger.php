<?php

namespace Zaynasheff\DocumentGenerator\Mergers;

use setasign\Fpdi\Fpdi;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;

final class PdfMerger
{
    /**
     * Merge PDF files into a single document.
     *
     * @param  iterable<string>  $files
     *
     * @throws DocumentGeneratorException
     */
    public function merge(
        iterable $files,
        string $destination
    ): string {
        $pdf = new Fpdi;

        foreach ($files as $file) {
            $this->importFile(
                $pdf,
                $file
            );
        }

        $pdf->Output(
            'F',
            $destination
        );

        return $destination;
    }

    /**
     * Import all pages from a PDF document.
     *
     * @throws DocumentGeneratorException
     */
    private function importFile(
        Fpdi $pdf,
        string $file
    ): void {
        if (! is_file($file)) {
            throw new DocumentGeneratorException(
                sprintf(
                    'PDF file "%s" does not exist.',
                    $file
                )
            );
        }

        $pageCount = $pdf->setSourceFile(
            $file
        );

        for ($page = 1; $page <= $pageCount; $page++) {

            $template = $pdf->importPage(
                $page
            );

            $size = $pdf->getTemplateSize(
                $template
            );

            if (! is_array($size)) {
                throw new DocumentGeneratorException(
                    sprintf(
                        'Unable to determine page size for "%s".',
                        $file
                    )
                );
            }

            $pdf->AddPage(
                $size['orientation'],
                [
                    $size['width'],
                    $size['height'],
                ]
            );

            $pdf->useTemplate(
                $template
            );
        }
    }
}
