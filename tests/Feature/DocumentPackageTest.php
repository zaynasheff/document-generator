<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;
use Zaynasheff\DocumentGenerator\Package\BlankPage;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class DocumentPackageTest extends TestCase
{
    public function test_package_is_empty_by_default(): void
    {
        $package = DocumentPackage::make();

        $this->assertTrue($package->isEmpty());
        $this->assertCount(0, $package->items());
    }

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
            $package->items()
        );

        $this->assertSame(
            1,
            $package->count()
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
            $package->items()
        );

        $this->assertSame(
            2,
            $package->count()
        );
    }

    public function test_can_get_first_item(): void
    {
        $package = DocumentPackage::make();

        $document = $package->addDocument();

        $this->assertSame(
            $document,
            $package->first()
        );
    }

    public function test_can_get_last_item(): void
    {
        $package = DocumentPackage::make();

        $package->addDocument();
        $package->addBlankPage();

        $this->assertInstanceOf(
            BlankPage::class,
            $package->last()
        );
    }

    public function test_can_set_output_directory(): void
    {
        $package = DocumentPackage::make();

        $package->output('/tmp/documents');

        $this->assertSame(
            '/tmp/documents',
            $package->outputDirectory()
        );
    }

    public function test_can_set_package_name(): void
    {
        $package = DocumentPackage::make();

        $package->name('contract_123');

        $this->assertSame(
            'contract_123',
            $package->packageName()
        );
    }

    public function test_generate_requires_output_directory(): void
    {
        $this->expectException(
            DocumentGeneratorException::class
        );

        $package = DocumentPackage::make();

        $package->addDocument();

        $package->generate();
    }

    public function test_generate_requires_documents(): void
    {
        $this->expectException(
            DocumentGeneratorException::class
        );

        $package = DocumentPackage::make();

        $package->output(
            __DIR__.'/../Fixtures/output'
        );

        $package->generate();
    }
}
