<?php

namespace Zaynasheff\DocumentGenerator\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Zaynasheff\DocumentGenerator\DocumentGeneratorServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DocumentGeneratorServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $binary = getenv('DOCUMENT_GENERATOR_OFFICE_BINARY');

        if ($binary === false) {
            return;
        }

        /** @var Repository $config */
        $config = $app->make(Repository::class);

        $config->set(
            'document-generator.libreoffice.binary',
            $binary
        );
    }
}
