<?php

namespace Zaynasheff\DocumentGenerator;

use Zaynasheff\DocumentGenerator\Result\GenerationResult;

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

    /**
     * @var bool
     */
    private $generateDocx = false;

    /**
     * @var string|null
     */
    private $output;


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

    public function docx(): self
    {
        $this->generateDocx = true;

        return $this;
    }
    public function saveTo(string $directory): self
    {
        $this->output = $directory;

        return $this;
    }

    public function generate(): GenerationResult
    {
        return new GenerationResult(
            null,
            null,
        );
    }
}