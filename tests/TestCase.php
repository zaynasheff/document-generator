<?php

namespace Zaynasheff\DocumentGenerator\Tests;

use Illuminate\Contracts\Config\Repository;
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
     * @param \Illuminate\Foundation\Application $app
     */
    protected function defineEnvironment($app): void
    {
        $command = getenv('DOCUMENT_GENERATOR_OFFICE_COMMAND');

        if ($command === false) {
            return;
        }

        /** @var Repository $config */
        $config = $app->make(Repository::class);

        $config->set(
            'document-generator.libreoffice.command',
            $command
        );
    }
}