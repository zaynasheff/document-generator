<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use PhpOffice\PhpWord\TemplateProcessor;

final class DocxGenerator
{

    /**
     * @param array<string, string|int|float|bool|null> $values
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