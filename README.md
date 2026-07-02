# Document Generator

Generate **DOCX** and **PDF** documents from Microsoft Word templates using **PHPWord** and **LibreOffice**.

Document Generator provides a simple and fluent API for generating DOCX documents from templates and optionally converting them to PDF.

---

## Features

- Generate DOCX documents from Microsoft Word templates
- Replace template placeholders
- Convert generated DOCX files to PDF
- Fluent and expressive API
- Laravel package auto-discovery
- Configurable LibreOffice binary
- PHPUnit tested
- PHPStan (max level)
- Laravel Pint
- GitHub Actions CI

---

## Requirements

- PHP 7.4+
- Laravel 8+
- LibreOffice (required only for PDF generation)

---

## Installation

Install the package using Composer:

```bash
composer require zaynasheff/document-generator
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=document-generator-config
```

---

## Configuration

Set the path to the LibreOffice executable in your `.env` file.

Default:

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=soffice
```

### macOS

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=/Applications/LibreOffice.app/Contents/MacOS/soffice
```

### Windows

```env
DOCUMENT_GENERATOR_OFFICE_BINARY="C:\Program Files\LibreOffice\program\soffice.exe"
```

### Linux

```env
DOCUMENT_GENERATOR_OFFICE_BINARY=/usr/bin/soffice
```

> **Note**
>
> If `soffice` is available in your system `PATH`, you can simply use:
>
> ```env
> DOCUMENT_GENERATOR_OFFICE_BINARY=soffice
> ```

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

Generate both DOCX and PDF:

```php
use Zaynasheff\DocumentGenerator\DocumentGenerator;

$result = DocumentGenerator::make()
    ->template(storage_path('templates/contract.docx'))
    ->values([
        'FIRST_NAME' => 'John',
        'LAST_NAME'  => 'Anderson',
        'CITY'       => 'Berlin',
    ])
    ->docx()
    ->pdf()
    ->output(storage_path('documents'))
    ->generate();
```

---

## Generate DOCX Only

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

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->pdf()
    ->output($output)
    ->generate();
```

---

## Generation Result

The `generate()` method returns a `GenerationResult` instance.

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

## API Reference

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

Generates the requested document(s) and returns a `GenerationResult`.

```php
$result = DocumentGenerator::make()
    ->template($template)
    ->values($values)
    ->docx()
    ->output($output)
    ->generate();
```

---

## Testing

Run PHPUnit:

```bash
composer test
```

Run PHPStan:

```bash
composer analyse
```

Run Pint:

```bash
composer format:test
```

Run all quality checks:

```bash
composer quality
```

Automatically fix code style:

```bash
composer fix
```

---

## Contributing

Contributions are welcome.

Before opening a Pull Request, please make sure all quality checks pass:

```bash
composer quality
```

---

## License

The MIT License (MIT).
