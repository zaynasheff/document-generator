<?php

namespace Zaynasheff\DocumentGenerator\Configuration;

final class DocumentGeneratorConfig
{
    /**
     * LibreOffice command.
     *
     * Examples:
     * - soffice
     * - /Applications/LibreOffice.app/Contents/MacOS/soffice
     * - docker exec libreoffice soffice
     *
     * @var string
     */
    private $officeCommand;

    /**
     * Process timeout in seconds.
     *
     * @var int
     */
    private $timeout;

    public function __construct(
        string $officeCommand = 'soffice',
        int $timeout = 60
    ) {
        $this->officeCommand = $officeCommand;
        $this->timeout = $timeout;
    }

    public function officeCommand(): string
    {
        return $this->officeCommand;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }
}
