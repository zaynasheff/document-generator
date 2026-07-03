<?php

namespace Zaynasheff\DocumentGenerator\Result;

final class PackageResult
{
    /**
     * @var GenerationResult[]
     */
    private array $results = [];

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
        return empty($this->results);
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
}
