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
            }
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
