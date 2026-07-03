<?php

namespace Zaynasheff\DocumentGenerator;

use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;
use Zaynasheff\DocumentGenerator\Generators\PackageGenerator;
use Zaynasheff\DocumentGenerator\Package\BlankPage;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Package\Item;
use Zaynasheff\DocumentGenerator\Result\PackageResult;

class DocumentPackage
{
    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * Output directory.
     */
    private ?string $output = null;

    /**
     * Package name.
     */
    private ?string $name = null;

    public static function make(): self
    {
        return new self;
    }

    public function addDocument(): Document
    {
        $document = new Document;

        $this->items[] = $document;

        return $document;
    }

    public function addBlankPage(): self
    {
        $this->items[] = new BlankPage;

        return $this;
    }

    public function output(string $directory): self
    {
        $this->output = $directory;

        return $this;
    }

    public function outputDirectory(): string
    {
        if ($this->output === null) {
            throw new DocumentGeneratorException(
                'Output directory is not specified.'
            );
        }

        return $this->output;
    }

    public function name(string $name): self
    {
        $this->name = trim($name);

        return $this;
    }

    public function packageName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Item[]
     */
    public function items(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function first(): ?Item
    {
        return $this->items[0] ?? null;
    }

    public function last(): ?Item
    {
        if ($this->items === []) {
            return null;
        }

        return $this->items[array_key_last($this->items)];
    }

    public function generate(): PackageResult
    {
        $this->validate();

        return (new PackageGenerator)->generate($this);
    }

    private function validate(): void
    {
        if ($this->isEmpty()) {
            throw new DocumentGeneratorException(
                'Package does not contain any items.'
            );
        }

        if ($this->output === null) {
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
