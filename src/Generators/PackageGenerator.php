<?php

namespace Zaynasheff\DocumentGenerator\Generators;

use Zaynasheff\DocumentGenerator\DocumentPackage;
use Zaynasheff\DocumentGenerator\Result\PackageResult;

class PackageGenerator
{
    public function generate(DocumentPackage $package): PackageResult
    {
        return new PackageResult;
    }
}
