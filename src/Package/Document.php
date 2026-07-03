<?php

namespace Zaynasheff\DocumentGenerator\Package;

class Document extends Item
{
    private ?string $template = null;

    public function template(string $path): self
    {
        $this->template = $path;

        return $this;
    }

    public function templatePath(): ?string
    {
        return $this->template;
    }
}
