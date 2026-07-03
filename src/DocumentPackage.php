<?php

namespace Zaynasheff\DocumentGenerator;

use Zaynasheff\DocumentGenerator\Generators\PackageGenerator;
use Zaynasheff\DocumentGenerator\Package\BlankPage;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Package\Item;
use Zaynasheff\DocumentGenerator\Result\PackageResult;

class DocumentPackage
{
    /**
     * @var array<Item>
     */
    private array $items = [];

    /**
     * Package output directory.
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

    public function outputDirectory(): ?string
    {
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
     * @return array<Item>
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
        $count = count($this->items);

        if ($count === 0) {
            return null;
        }

        return $this->items[$count - 1];
    }

    public function generate(): PackageResult
    {
        return (new PackageGenerator)->generate($this);
    }
}
