<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Factories\DocumentGeneratorFactory;
use Zaynasheff\DocumentGenerator\Mergers\PdfMerger;
use Zaynasheff\DocumentGenerator\Package\BlankPage;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Result\PackageResult;

final class PackageGenerator
{
    private DocumentGeneratorFactory $factory;

    private PdfMerger $pdfMerger;

    private BlankPageGenerator $blankPageGenerator;

    public function __construct(
        ?DocumentGeneratorFactory $factory = null,
        ?PdfMerger $pdfMerger = null,
        ?BlankPageGenerator $blankPageGenerator = null
    ) {
        $this->factory = $factory ?? new DocumentGeneratorFactory;
        $this->pdfMerger = $pdfMerger ?? new PdfMerger;
        $this->blankPageGenerator = $blankPageGenerator ?? new BlankPageGenerator;
    }

    public function generate(
        DocumentPackage $package
    ): PackageResult {
        $result = new PackageResult;

        $pdfFiles = [];
        $temporaryFiles = [];

        foreach ($package->items() as $item) {

            if ($item instanceof BlankPage) {

                $blankPdf = $this
                    ->blankPageGenerator
                    ->temporary();

                $temporaryFiles[] = $blankPdf;
                $pdfFiles[] = $blankPdf;

                continue;
            }

            if (! $item instanceof Document) {
                continue;
            }

            for (
                $copy = 1;
                $copy <= $item->copiesCount();
                $copy++
            ) {
                $generator = $this->factory->make($item);

                $generator
                    ->output(
                        $package->outputDirectory()
                    )
                    ->name(
                        $this->documentName(
                            $item,
                            $copy
                        )
                    );

                $generationResult = $generator->generate();

                $result->addResult(
                    $generationResult
                );

                if ($generationResult->hasPdf()) {

                    $pdf = $generationResult->pdfPath();

                    if ($pdf !== null) {
                        $pdfFiles[] = $pdf;
                    }
                }
            }
        }

        if (
            $package->shouldMergePdf()
            && $pdfFiles !== []
        ) {
            $destination = rtrim(
                $package->outputDirectory(),
                DIRECTORY_SEPARATOR
            )
                .DIRECTORY_SEPARATOR
                .($package->packageName() ?? 'package')
                .'.pdf';

            $this->pdfMerger->merge(
                $pdfFiles,
                $destination
            );

            $result->mergedPdf(
                $destination
            );

            foreach ($temporaryFiles as $file) {

                if (is_file($file)) {
                    unlink($file);
                }
            }
        }

        return $result;
    }

    private function documentName(
        Document $document,
        int $copy
    ): string {
        $name = $document->documentName();

        if ($name === null) {
            $template = $document->templatePath();

            $name = pathinfo(
                $template ?? 'document',
                PATHINFO_FILENAME
            );
        }

        if ($document->copiesCount() === 1) {
            return $name;
        }

        if ($copy === 1) {
            return $name;
        }

        return sprintf(
            '%s_%d',
            $name,
            $copy
        );
    }
}
