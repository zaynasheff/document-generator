<?php

namespace Zaynasheff\DocumentGenerator\Result;

final class PackageResult
{
    /**
     * @var GenerationResult[]
     */
    private array $results = [];

    private ?string $mergedPdf = null;

    public function addResult(
        GenerationResult $result
    ): self {
        $this->results[] = $result;

        return $this;
    }

    /**
     * @return GenerationResult[]
     */
    public function results(): array
    {
        return $this->results;
    }

    public function count(): int
    {
        return count($this->results);
    }

    public function isEmpty(): bool
    {
        return $this->results === [];
    }

    public function first(): ?GenerationResult
    {
        return $this->results[0] ?? null;
    }

    public function last(): ?GenerationResult
    {
        if ($this->results === []) {
            return null;
        }

        return $this->results[array_key_last($this->results)];
    }

    public function mergedPdf(
        string $path
    ): self {
        $this->mergedPdf = $path;

        return $this;
    }

    public function hasMergedPdf(): bool
    {
        return $this->mergedPdf !== null;
    }

    public function mergedPdfPath(): ?string
    {
        return $this->mergedPdf;
    }
}
