<?php

namespace Zaynasheff\DocumentGenerator;

use Zaynasheff\DocumentGenerator\Package\BlankPage;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Package\Item;

class DocumentPackage
{
    /**
     * @var array<Item>
     */
    private array $items = [];

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

    /**
     * @return array<Item>
     */
    public function all(): array
    {
        return $this->items;
    }
}
