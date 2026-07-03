<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use PhpOffice\PhpWord\TemplateProcessor;

final class DocxGenerator
{
    /**
     * @param  array<string, string|int|float|bool|null>  $values
     */
    public function generate(
        string $template,
        array $values,
        string $path
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

        $processor->saveAs(
            $path
        );

        return $path;
    }
}
