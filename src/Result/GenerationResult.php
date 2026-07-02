<?php

namespace Zaynasheff\DocumentGenerator\Result;

final class GenerationResult
{
    /**
     * @var string|null
     */
    private $docxPath;

    /**
     * @var string|null
     */
    private $pdfPath;

    public function __construct(
        ?string $docxPath,
        ?string $pdfPath
    ) {
        $this->docxPath = $docxPath;
        $this->pdfPath = $pdfPath;
    }

    public function hasDocx(): bool
    {
        return $this->docxPath !== null;
    }

    public function hasPdf(): bool
    {
        return $this->pdfPath !== null;
    }

    public function docxPath(): ?string
    {
        return $this->docxPath;
    }

    public function pdfPath(): ?string
    {
        return $this->pdfPath;
    }
}
