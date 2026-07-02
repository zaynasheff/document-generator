<?php

namespace Zaynasheff\DocumentGenerator\Tests;

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

    protected function defineEnvironment($app): void
    {
        $command = getenv('DOCUMENT_GENERATOR_OFFICE_COMMAND');

        if ($command !== false) {
            $app['config']->set(
                'document-generator.libreoffice.command',
                $command
            );
        }
    }
}