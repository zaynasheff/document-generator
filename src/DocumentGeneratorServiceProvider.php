<?php

namespace Zaynasheff\DocumentGenerator;

use Illuminate\Support\ServiceProvider;

class DocumentGeneratorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/document-generator.php',
            'document-generator'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/document-generator.php' => config_path('document-generator.php'),
        ], 'document-generator-config');
    }
}