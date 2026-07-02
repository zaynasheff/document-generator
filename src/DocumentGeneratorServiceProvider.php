<?php

namespace Zaynasheff\DocumentGenerator;

use Illuminate\Support\ServiceProvider;
use Zaynasheff\DocumentGenerator\Configuration\DocumentGeneratorConfig;

class DocumentGeneratorServiceProvider extends ServiceProvider
{


    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/document-generator.php',
            'document-generator'
        );

        $this->app->singleton(DocumentGeneratorConfig::class, function ($app) {
            return new DocumentGeneratorConfig(
                $app['config']->get(
                    'document-generator.libreoffice.command',
                    'soffice'
                ),
                $app['config']->get(
                    'document-generator.libreoffice.timeout',
                    60
                )
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/document-generator.php' => config_path('document-generator.php'),
        ], 'document-generator-config');
    }
}