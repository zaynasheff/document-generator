# Document Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zaynasheff/document-generator.svg?style=flat-square)](...)
[![Tests](https://img.shields.io/github/actions/workflow/status/zaynasheff/document-generator/tests.yml?branch=main)](...)
[![PHP Version](https://img.shields.io/packagist/php-v/zaynasheff/document-generator)](...)
[![License](https://img.shields.io/packagist/l/zaynasheff/document-generator)](...)

Generate **DOCX**, **PDF**, and **merged PDF packages** from Microsoft Word templates using **PHPWord** and **LibreOffice**.

Document Generator is a Laravel package that provides a fluent API for generating documents from DOCX templates, converting them to PDF, generating multiple documents in a single operation, and merging them into one PDF package.

## Contents

- Features
- Installation
- Configuration
- Basic Usage
- Package Generation
- API Reference
- Testing
- Contributing
- License

---

## Features

- Generate DOCX documents from Microsoft Word templates
- Replace template placeholders
- Generate PDF documents using LibreOffice
- Generate multiple documents in a single operation
- Merge generated PDF documents into a single package
- Custom output filenames
- Fluent and expressive API
- Laravel auto-discovery
- Configurable LibreOffice executable
- PHPUnit tested
- PHPStan (max level)
- Laravel Pint
- GitHub Actions ready

---

## Requirements

- PHP 7.4 or higher
- Laravel 8+
- LibreOffice (required only for PDF generation)

---

## Installation

Install the package using Composer.

```bash
composer require zaynasheff/document-generator
```

Publish the configuration file.

```bash
php artisan vendor:publish --tag=document-generator-config
```

---

## Configuration

Set the LibreOffice executable in your `.env`.

Default:

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=soffice
```

### macOS

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=/Applications/LibreOffice.app/Contents/MacOS/soffice
```

### Linux

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=/usr/bin/soffice
```

### Windows

```env
DOCUMENT_GENERATOR_OFFICE_BINARY="C:\Program Files\LibreOffice\program\soffice.exe"
```

If LibreOffice is available in your system `PATH`, simply use:

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=soffice
```

---

## Configuration File

```php
return [

    'libreoffice' => [

        'binary' => env(
            'DOCUMENT_GENERATOR_OFFICE_BINARY',
            'soffice'
        ),

        'timeout' => 60,

    ],

];
```

---

## Basic Usage

Generate both DOCX and PDF.

```php
use Zaynasheff\DocumentGenerator\DocumentGenerator;

$result = DocumentGenerator::make()
    ->template(
        storage_path('templates/contract.docx')
    )
    ->values([
        'FIRST_NAME' => 'John',
        'LAST_NAME'  => 'Anderson',
        'CITY'       => 'Berlin',
    ])
    ->docx()
    ->pdf()
    ->output(
        storage_path('documents')
    )
    ->generate();
```

---

## Generate DOCX Only

Generate a Microsoft Word document without creating a PDF.

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->docx()
    ->output($output)
    ->generate();
```

---

## Generate PDF Only

Generate only a PDF document.

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->pdf()
    ->output($output)
    ->generate();
```

---

## Generate DOCX and PDF

Generate both formats in a single operation.

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->docx()
    ->pdf()
    ->output($output)
    ->generate();
```

---

## Custom Output Filename

By default the generated filename is based on the template filename.

Use `name()` to specify your own filename.

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->name('contract_001')
    ->pdf()
    ->output($output)
    ->generate();
```

Generated files:

```
contract_001.docx
contract_001.pdf
```

---

## Generation Result

The `generate()` method returns a `GenerationResult`.

```php
$result->hasDocx();

$result->docxPath();

$result->hasPdf();

$result->pdfPath();
```

Example:

```php
if ($result->hasPdf()) {

    return response()->download(
        $result->pdfPath()
    );

}
```

---

## Multiple Placeholder Values

Template placeholders are passed as an associative array.

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values([
        'FIRST_NAME' => 'John',
        'LAST_NAME'  => 'Anderson',
        'CITY'       => 'Berlin',
        'EMAIL'      => 'john@example.com',
        'PHONE'      => '+1 555 123 45 67',
    ])
    ->pdf()
    ->output($output)
    ->generate();
```

---

## Supported Placeholder Types

The following value types are supported:

- string
- integer
- float
- boolean
- null

Example:

```php
->values([
    'NAME'      => 'John',
    'AGE'       => 35,
    'BALANCE'   => 1500.75,
    'ACTIVE'    => true,
    'COMMENT'   => null,
])
```

---

# Package Generation

Generate multiple documents in a single operation.

```php
use Zaynasheff\DocumentGenerator\DocumentPackage;

$package = DocumentPackage::make();

$package->output(
    storage_path('documents')
);

$package
    ->addDocument()
    ->template(
        storage_path('templates/contract.docx')
    )
    ->values([
        'FIRST_NAME' => 'John',
        'LAST_NAME'  => 'Anderson',
    ])
    ->name('contract')
    ->pdf();

$package
    ->addDocument()
    ->template(
        storage_path('templates/invoice.docx')
    )
    ->values([
        'FIRST_NAME' => 'John',
    ])
    ->name('invoice')
    ->pdf();

$result = $package->generate();
```

Generated files:

```
documents/
    contract.docx
    contract.pdf

    invoice.docx
    invoice.pdf
```

---

# Merge PDF

Automatically merge all generated PDF files into a single document.

```php
$package = DocumentPackage::make();

$package
    ->output(
        storage_path('documents')
    )
    ->name('package')
    ->mergePdf();

$package
    ->addDocument()
    ->template($contractTemplate)
    ->values($contractValues)
    ->name('contract')
    ->pdf();

$package
    ->addDocument()
    ->template($invoiceTemplate)
    ->values($invoiceValues)
    ->name('invoice')
    ->pdf();

$result = $package->generate();
```

Generated files:

```
documents/

    contract.docx
    contract.pdf

    invoice.docx
    invoice.pdf

    package.pdf
```

---

# Package Result

Package generation returns a `PackageResult`.

```php
$result->count();

$result->results();

$result->hasMergedPdf();

$result->mergedPdfPath();
```

Example:

```php
if ($result->hasMergedPdf()) {

    return response()->download(
        $result->mergedPdfPath()
    );

}
```

---

# Complete Package Example

```php
use Zaynasheff\DocumentGenerator\DocumentPackage;

$package = DocumentPackage::make();

$package
    ->output(
        storage_path('documents')
    )
    ->name('contracts')
    ->mergePdf();

$package
    ->addDocument()
    ->template(
        storage_path('templates/contract.docx')
    )
    ->values([
        'FIRST_NAME' => 'John',
        'LAST_NAME'  => 'Anderson',
        'CITY'       => 'Berlin',
    ])
    ->name('contract')
    ->pdf();

$package
    ->addDocument()
    ->template(
        storage_path('templates/invoice.docx')
    )
    ->values([
        'FIRST_NAME' => 'John',
        'AMOUNT'     => '1500 €',
    ])
    ->name('invoice')
    ->pdf();

$result = $package->generate();

if ($result->hasMergedPdf()) {

    return response()->download(
        $result->mergedPdfPath()
    );

}
```

---

# Current Capabilities

✅ DOCX generation

✅ PDF generation

✅ Custom output filenames

✅ Multiple document generation

✅ PDF package generation

✅ Merged PDF packages

✅ Fluent API

✅ Laravel auto-discovery


---

# Why Document Generator?

Document Generator focuses on simplicity and readability.

```php
$result = DocumentPackage::make()
    ->output($output)
    ->name('contracts')
    ->mergePdf();

$result
    ->addDocument()
    ->template($contract)
    ->values($contractData)
    ->pdf();

$result
    ->addDocument()
    ->template($invoice)
    ->values($invoiceData)
    ->pdf();

$result = $result->generate();
```

The package hides all low-level details of DOCX generation, PDF conversion and PDF merging behind a clean, fluent API.


---

# API Reference

## DocumentGenerator

### template(string $template)

Sets the DOCX template file.

```php
->template($template)
```

---

### values(array $values)

Sets template placeholder values.

```php
->values([
    'FIRST_NAME' => 'John',
    'LAST_NAME'  => 'Smith',
])
```

---

### name(string $name)

Sets the output filename without extension.

```php
->name('contract')
```

---

### docx()

Enables DOCX generation.

```php
->docx()
```

---

### pdf()

Enables PDF generation.

```php
->pdf()
```

---

### output(string $directory)

Sets the output directory.

```php
->output(storage_path('documents'))
```

---

### generate()

Generates the requested document(s).

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->pdf()
    ->output($output)
    ->generate();
```

---

# DocumentPackage

### addDocument()

Adds a document to the package.

```php
$package->addDocument();
```

---

### output(string $directory)

Sets the package output directory.

```php
$package->output(
    storage_path('documents')
);
```

---

### name(string $name)

Sets the merged PDF filename.

```php
$package->name('contracts');
```

Produces:

```
contracts.pdf
```

---

### mergePdf()

Enables automatic PDF merging.

```php
$package->mergePdf();
```

---

### generate()

Generates the complete package.

```php
$result = $package->generate();
```

---

# GenerationResult

```php
$result->hasDocx();

$result->docxPath();

$result->hasPdf();

$result->pdfPath();
```

---

# PackageResult

```php
$result->count();

$result->results();

$result->hasMergedPdf();

$result->mergedPdfPath();
```

---

# Error Handling

All package exceptions extend:

```php
Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException
```

Example:

```php
use Zaynasheff\DocumentGenerator\Exceptions\DocumentGeneratorException;

try {

    $result = DocumentGenerator::make()
        ->template($template)
        ->pdf()
        ->output($output)
        ->generate();

} catch (DocumentGeneratorException $exception) {

    report($exception);

}
```

---

# Testing

Run PHPUnit.

```bash
composer test
```

Run PHPStan.

```bash
composer analyse
```

Run Laravel Pint.

```bash
composer format:test
```

Run the complete quality pipeline.

```bash
composer quality
```

Automatically fix coding style.

```bash
composer fix
```

---

# Contributing

Contributions are welcome.

Before opening a Pull Request, please make sure all quality checks pass.

```bash
composer quality
```

Please follow the existing coding style and architecture.

---

# Roadmap

The following features are planned for future releases.

- Blank pages inside document packages
- Multiple document copies
- ZIP package generation
- Watermarks
- Page numbering
- Digital signatures

---

# License

The MIT License (MIT).

---

Made with ❤️ for the Laravel community.
