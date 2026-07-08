<?php

namespace Zaynasheff\DocumentGenerator\Converters;

use Zaynasheff\DocumentGenerator\Configuration\DocumentGeneratorConfig;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;
use Zaynasheff\DocumentGenerator\Process\ProcessRunner;

final class PdfConverter
{
    /**
     * @var ProcessRunner
     */
    private $runner;

    /**
     * @var DocumentGeneratorConfig
     */
    private $config;

    public function __construct(
        DocumentGeneratorConfig $config,
        ?ProcessRunner $runner = null
    ) {
        $this->config = $config;
        $this->runner = $runner ?? new ProcessRunner;
    }

    /**
     * Convert DOCX document to PDF.
     *
     * @throws DocumentGeneratorException
     */
    public function convert(
        string $docx,
        string $outputDirectory
    ): string {
        $command = $this->buildLibreOfficeCommand(
            $docx,
            $outputDirectory
        );

        $this->runner->run($command);

        $pdf = $outputDirectory
            .DIRECTORY_SEPARATOR
            .pathinfo($docx, PATHINFO_FILENAME)
            .'.pdf';

        if (! is_file($pdf)) {
            throw new DocumentGeneratorException(
                'PDF file was not generated.'
            );
        }

        return $pdf;
    }

    /**
     * Checks whether LibreOffice command is available.
     */
    public function isAvailable(): bool
    {
        exec(
            $this->config->officeCommand().' --version 2>&1',
            $output,
            $exitCode
        );

        return $exitCode === 0;
    }

    /**
     * Builds LibreOffice conversion command.
     */
    private function buildLibreOfficeCommand(
        string $docx,
        string $outputDirectory
    ): string {
        $command = [
            $this->config->officeCommand(),
        ];

        if ($this->config->officeProfile() !== null) {

            if (! is_dir($this->config->officeProfile())) {
                mkdir($this->config->officeProfile(), 0777, true);
            }

            $command[] = sprintf(
                '-env:UserInstallation=file://%s',
                $this->config->officeProfile(),
            );
        }

        $command[] = '--headless';
        $command[] = '--convert-to';
        $command[] = 'pdf';
        $command[] = '--outdir';
        $command[] = escapeshellarg($outputDirectory);
        $command[] = escapeshellarg($docx);

        return implode(' ', $command);
    }
}
