<?php

namespace Zaynasheff\DocumentGenerator\Package;

use Zaynasheff\DocumentGenerator\DocumentFormat;

class Document extends Item
{
    /**
     * Template path.
     */
    private ?string $template = null;

    /**
     * Template placeholders.
     *
     * @var array<string, scalar|null>
     */
    private array $values = [];

    /**
     * Output formats.
     *
     * @var list<string>
     */
    private array $formats = [];

    /**
     * Output filename without extension.
     */
    private ?string $name = null;

    public function template(string $path): self
    {
        $this->template = $path;

        return $this;
    }

    /**
     * @param  array<string, scalar|null>  $values
     */
    public function values(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = pathinfo(
            trim($name),
            PATHINFO_FILENAME
        );

        return $this;
    }

    public function docx(): self
    {
        return $this->addFormat(
            DocumentFormat::DOCX
        );
    }

    public function pdf(): self
    {
        return $this->addFormat(
            DocumentFormat::PDF
        );
    }

    public function templatePath(): ?string
    {
        return $this->template;
    }

    /**
     * @return array<string, scalar|null>
     */
    public function placeholders(): array
    {
        return $this->values;
    }

    public function documentName(): ?string
    {
        return $this->name;
    }

    /**
     * @return list<string>
     */
    public function formats(): array
    {
        return $this->formats;
    }

    public function hasFormat(string $format): bool
    {
        return in_array(
            $format,
            $this->formats,
            true
        );
    }

    private function addFormat(string $format): self
    {
        if (! $this->hasFormat($format)) {
            $this->formats[] = $format;
        }

        return $this;
    }

    private int $copies = 1;

    public function copies(int $count): self
    {
        $this->copies = max(
            1,
            $count
        );

        return $this;
    }

    public function copiesCount(): int
    {
        return $this->copies;
    }
}
