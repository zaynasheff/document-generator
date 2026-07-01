<?php

namespace Zaynasheff\DocumentGenerator;

final class DocumentGenerator
{
    /**
     * @var string|null
     */
    private $template;

    /**
     * @var array
     */
    private $values = [];


    private function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    public function template(string $path): self
    {
        $this->template = $path;

        return $this;
    }
    public function values(array $values): self
    {
        $this->values = $values;

        return $this;
    }
}