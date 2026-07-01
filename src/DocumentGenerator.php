<?php

namespace Zaynasheff\DocumentGenerator;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use Zaynasheff\DocumentGenerator\Result\GenerationResult;

final class DocumentGenerator
{
    /**
     * @var string|null
     */
    private $template;

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var string[]
     */
    private $formats = [];

    /**
     * @var string|null
     */
    private $output;

    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    public function template(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function values(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function docx(): self
    {
        if (! in_array(DocumentFormat::DOCX, $this->formats, true)) {
            $this->formats[] = DocumentFormat::DOCX;
        }

        return $this;
    }

    public function output(string $output): self
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function generate(): GenerationResult
    {
        $docxPath = null;

        if (in_array(DocumentFormat::DOCX, $this->formats, true)) {

            $processor = new TemplateProcessor(
                $this->template
            );

            foreach ($this->values as $key => $value) {
                $processor->setValue(
                    $key,
                    (string) $value
                );
            }

            $docxPath = rtrim(
                    $this->output,
                    DIRECTORY_SEPARATOR
                ) . DIRECTORY_SEPARATOR . basename(
                    $this->template
                );

            $processor->saveAs(
                $docxPath
            );
        }

        return new GenerationResult(
            $docxPath,
            null
        );
    }
}