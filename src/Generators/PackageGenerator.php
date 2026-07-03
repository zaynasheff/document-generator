<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Factories\DocumentGeneratorFactory;
use Zaynasheff\DocumentGenerator\Package\Document;
use Zaynasheff\DocumentGenerator\Result\PackageResult;

final class PackageGenerator
{
    public function generate(
        DocumentPackage $package
    ): PackageResult {

        $result = new PackageResult;

        $factory = new DocumentGeneratorFactory;

        foreach ($package->items() as $item) {

            if (! $item instanceof Document) {
                continue;
            }

            $generator = $factory->make($item);

            $generator->output(
                $package->outputDirectory()
            );

            $generationResult = $generator->generate();

            $result->addResult(
                $generationResult
            );
        }

        return $result;
    }
}
