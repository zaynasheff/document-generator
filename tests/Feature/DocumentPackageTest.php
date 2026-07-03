<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class DocumentPackageTest extends TestCase
{
    public function test_can_add_document(): void
    {
        $package = DocumentPackage::make();

        $document = $package->addDocument();

        $this->assertInstanceOf(
            Document::class,
            $document
        );

        $this->assertCount(
            1,
            $package->all()
        );
    }

    public function test_can_add_blank_page(): void
    {
        $package = DocumentPackage::make();

        $package
            ->addDocument()
            ->template('/templates/contract.docx');

        $package->addBlankPage();

        $this->assertCount(
            2,
            $package->all()
        );
    }
}
