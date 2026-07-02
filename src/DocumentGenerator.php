<?php

namespace Zaynasheff\DocumentGenerator;

use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use Zaynasheff\DocumentGenerator\Converters\PdfConverter;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;
use Zaynasheff\DocumentGenerator\Generators\DocxGenerator;
use Zaynasheff\DocumentGenerator\Result\GenerationResult;

final class DocumentGenerator
{
    /**
     * Template path.
     */
    private string $template;

    /**
     * Template values.
     *
     * @var array<string, string|int|float|bool|null>
     */
    private array $values = [];

    /**
     * Output formats.
     *
     * @var string[]
     */
    private array $formats = [];

    /**
     * Output directory.
     */
    private string $output;

    private DocxGenerator $docxGenerator;

    private PdfConverter $pdfConverter;

    public function __construct(
        DocxGenerator $docxGenerator,
        PdfConverter $pdfConverter
    ) {
        $this->docxGenerator = $docxGenerator;
        $this->pdfConverter = $pdfConverter;
    }

    /**
     * Create a new document generator instance.
     */
    public static function make(): self
    {
        return app(self::class);
    }

    public function template(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param  array<string, string|int|float|bool|null>  $values
     */
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

    public function pdf(): self
    {
        if (! in_array(DocumentFormat::PDF, $this->formats, true)) {
            $this->formats[] = DocumentFormat::PDF;
        }

        return $this;
    }

    /**
     * Sets output directory.
     */
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
        $this->validate();

        $docxPath = null;
        $pdfPath = null;

        if (in_array(DocumentFormat::DOCX, $this->formats, true)) {
            $docxPath = $this->docxGenerator->generate(
                $this->template,
                $this->values,
                $this->output
            );
        }

        if (in_array(DocumentFormat::PDF, $this->formats, true)) {

            if ($docxPath === null) {
                $docxPath = $this->docxGenerator->generate(
                    $this->template,
                    $this->values,
                    $this->output
                );
            }

            $pdfPath = $this->pdfConverter->convert(
                $docxPath,
                $this->output
            );
        }

        return new GenerationResult(
            $docxPath,
            $pdfPath
        );
    }

    private function validate(): void
    {
        if (! isset($this->template)) {
            throw new DocumentGeneratorException(
                'Template is not specified.'
            );
        }

        if (! is_file($this->template)) {
            throw new DocumentGeneratorException(
                'Template file does not exist.'
            );
        }

        if (empty($this->formats)) {
            throw new DocumentGeneratorException(
                'No output format specified.'
            );
        }

        if (! isset($this->output)) {
            throw new DocumentGeneratorException(
                'Output directory is not specified.'
            );
        }

        if (! is_dir($this->output)) {
            throw new DocumentGeneratorException(
                'Output directory does not exist.'
            );
        }

        if (! is_writable($this->output)) {
            throw new DocumentGeneratorException(
                'Output directory is not writable.'
            );
        }
    }
}
