<?php

namespace Zaynasheff\DocumentGenerator\Factories;

use Zaynasheff\DocumentGenerator\DocumentFormat;
use Zaynasheff\DocumentGenerator\DocumentGenerator;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;
use Zaynasheff\DocumentGenerator\Package\Document;

final class DocumentGeneratorFactory
{
    public function make(Document $document): DocumentGenerator
    {
        $template = $document->templatePath();

        if ($template === null) {
            throw new DocumentGeneratorException(
                'Document template is not specified.'
            );
        }

        $generator = DocumentGenerator::make()
            ->template($template)
            ->values($document->placeholders());

        foreach ($document->formats() as $format) {
            switch ($format) {
                case DocumentFormat::DOCX:
                    $generator->docx();
                    break;

                case DocumentFormat::PDF:
                    $generator->pdf();
                    break;
            }
        }

        return $generator;
    }
}
