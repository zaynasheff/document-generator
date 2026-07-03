<?php

namespace Zaynasheff\DocumentGenerator\Package;

use Zaynasheff\DocumentGenerator\DocumentFormat;

class Document extends Item
{
    private ?string $template = null;

    /**
     * @var array<string, scalar|null>
     */
    private array $values = [];

    /**
     * @var array<string>
     */
    private array $formats = [];

    public function template(string $path): self
    {
        $this->template = $path;

        return $this;
    }

    public function templatePath(): ?string
    {
        return $this->template;
    }

    /**
     * @param  array<string, scalar|null>  $values
     */
    public function values(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return array<string, scalar|null>
     */
    public function placeholders(): array
    {
        return $this->values;
    }

    public function docx(): self
    {
        return $this->addFormat(DocumentFormat::DOCX);
    }

    public function pdf(): self
    {
        return $this->addFormat(DocumentFormat::PDF);
    }

    public function hasFormat(string $format): bool
    {
        return in_array($format, $this->formats, true);
    }

    /**
     * @return array<string>
     */
    public function formats(): array
    {
        return $this->formats;
    }

    private function addFormat(string $format): self
    {
        if (! in_array($format, $this->formats, true)) {
            $this->formats[] = $format;
        }

        return $this;
    }
}
