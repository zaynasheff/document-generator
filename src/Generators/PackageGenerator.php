<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Factories\DocumentGeneratorFactory;
use Zaynasheff\DocumentGenerator\Mergers\PdfMerger;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Result\PackageResult;

final class PackageGenerator
{
    private DocumentGeneratorFactory $factory;

    private PdfMerger $pdfMerger;

    public function __construct(
        ?DocumentGeneratorFactory $factory = null,
        ?PdfMerger $pdfMerger = null
    ) {
        $this->factory = $factory ?? new DocumentGeneratorFactory;
        $this->pdfMerger = $pdfMerger ?? new PdfMerger;
    }

    public function generate(
        DocumentPackage $package
    ): PackageResult {
        $result = new PackageResult;

        foreach ($package->items() as $item) {

            if (! $item instanceof Document) {
                continue;
            }

            $generator = $this->factory->make($item);

            $generator->output(
                $package->outputDirectory()
            );

            $generationResult = $generator->generate();

            $result->addResult(
                $generationResult
            );
        }

        if ($package->shouldMergePdf()) {
            $this->mergePdf(
                $package,
                $result
            );
        }

        return $result;
    }

    private function mergePdf(
        DocumentPackage $package,
        PackageResult $result
    ): void {
        $files = [];

        foreach ($result->results() as $generationResult) {

            if (! $generationResult->hasPdf()) {
                continue;
            }

            $pdf = $generationResult->pdfPath();

            if ($pdf !== null) {
                $files[] = $pdf;
            }
        }

        if ($files === []) {
            return;
        }

        $destination = rtrim(
            $package->outputDirectory(),
            DIRECTORY_SEPARATOR
        )
            .DIRECTORY_SEPARATOR
            .($package->packageName() ?? 'package')
            .'.pdf';

        $this->pdfMerger->merge(
            $files,
            $destination
        );

        $result->mergedPdf(
            $destination
        );
    }
}
