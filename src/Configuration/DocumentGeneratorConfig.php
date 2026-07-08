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
     */
    private string $officeCommand;

    /**
     * Process timeout in seconds.
     */
    private int $timeout;

    /**
     * LibreOffice user profile.
     */
    private ?string $officeProfile;

    public function __construct(
        string $officeCommand = 'soffice',
        int $timeout = 60,
        ?string $officeProfile = null
    ) {
        $this->officeCommand = $officeCommand;
        $this->timeout = $timeout;
        $this->officeProfile = $officeProfile;
    }

    public function officeCommand(): string
    {
        return $this->officeCommand;
    }

    public function timeout(): int
    {
        return $this->timeout;
    }

    public function officeProfile(): ?string
    {
        return $this->officeProfile;
    }
}
