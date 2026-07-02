<?php

namespace Zaynasheff\DocumentGenerator;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use UnexpectedValueException;
use Zaynasheff\DocumentGenerator\Configuration\DocumentGeneratorConfig;

class DocumentGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/document-generator.php',
            'document-generator'
        );

        $this->app->singleton(
            DocumentGeneratorConfig::class,
            function (Container $app): DocumentGeneratorConfig {

                /** @var Repository $config */
                $config = $app->make(Repository::class);

                $command = $config->get(
                    'document-generator.libreoffice.command',
                    'soffice'
                );

                if (! is_string($command)) {
                    throw new UnexpectedValueException(
                        'The "document-generator.libreoffice.command" configuration value must be a string.'
                    );
                }

                $timeout = $config->get(
                    'document-generator.libreoffice.timeout',
                    60
                );

                if (! is_int($timeout)) {
                    throw new UnexpectedValueException(
                        'The "document-generator.libreoffice.timeout" configuration value must be an integer.'
                    );
                }

                return new DocumentGeneratorConfig(
                    $command,
                    $timeout
                );
            }
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/document-generator.php' => config_path('document-generator.php'),
        ], 'document-generator-config');
    }
}
