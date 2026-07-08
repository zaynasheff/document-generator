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

                $binary = $config->get(
                    'document-generator.libreoffice.binary',
                    'soffice'
                );

                if (! is_string($binary)) {
                    throw new UnexpectedValueException(
                        'The "document-generator.libreoffice.binary" configuration value must be a string.'
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

                $profile = $config->get(
                    'document-generator.libreoffice.profile'
                );

                if (! is_null($profile) && ! is_string($profile)) {
                    throw new UnexpectedValueException(
                        'The "document-generator.libreoffice.profile" configuration value must be a string or null.'
                    );
                }

                return new DocumentGeneratorConfig(
                    $binary,
                    $timeout,
                    $profile,
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
