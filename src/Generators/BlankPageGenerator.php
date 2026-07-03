<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use FPDF;

final class BlankPageGenerator
{
    /**
     * Generate a single blank PDF page.
     */
    public function generate(string $destination): string
    {
        $pdf = new FPDF(
            'P',
            'mm',
            'A4'
        );

        $pdf->AddPage();

        $pdf->Output(
            'F',
            $destination
        );

        return $destination;
    }

    /**
     * Create a temporary blank PDF.
     */
    public function temporary(): string
    {
        $file = tempnam(
            sys_get_temp_dir(),
            'blank_page_'
        );

        if ($file === false) {
            throw new \RuntimeException(
                'Unable to create temporary file.'
            );
        }

        $pdf = $file.'.pdf';

        if (file_exists($file)) {
            unlink($file);
        }

        return $this->generate(
            $pdf
        );
    }
}
