<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

final class DocxGenerator
{
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function generate(
        string $template,
        array $values,
        string $output
    ): string {

        $processor = new TemplateProcessor(
            $template
        );

        foreach ($values as $key => $value) {
            $processor->setValue(
                $key,
                (string) $value
            );
        }

        $path = rtrim(
                $output,
                DIRECTORY_SEPARATOR
            ) . DIRECTORY_SEPARATOR . basename(
                $template
            );

        $processor->saveAs(
            $path
        );

        return $path;
    }


}