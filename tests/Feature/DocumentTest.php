<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class DocumentTest extends TestCase
{
    public function test_can_set_template(): void
    {
        $document = new Document;

        $document->template(
            '/templates/contract.docx'
        );

        $this->assertSame(
            '/templates/contract.docx',
            $document->templatePath()
        );
    }
}
