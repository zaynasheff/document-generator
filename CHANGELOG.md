# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

## [0.5.0]

### Added

* Optional LibreOffice profile configuration
* `DOCUMENT_GENERATOR_OFFICE_PROFILE` environment variable
* Automatic LibreOffice profile directory creation

### Changed

* Improved LibreOffice compatibility in Docker and PHP-FPM environments
* Improved PDF conversion reliability in headless environments


## [0.4.0]

### Added

- Blank page support in document packages
- BlankPageGenerator
- Blank page integration tests

### Changed

- Improved PDF package generation
- Added validation for blank pages without PDF merging

## [0.3.0]

### Added

- Multiple document copies
- `Document::copies()`
- Automatic copy filename generation
- Integration tests for document copies

### Changed

- Improved package generation pipeline

---

## [0.2.0]

### Added

- Package document generation
- Multiple document generation
- PDF package merging
- Custom output filenames
- `DocumentPackage`
- `PackageGenerator`
- `DocumentGeneratorFactory`
- `PackageResult`
- `PdfMerger`
- Integration tests for package generation
- Integration tests for PDF merging

### Changed

- Refactored the document generation pipeline
- Improved package architecture
- Improved test coverage
- Updated project documentation

---

## [0.1.1]

### Added

- Laravel 13 support

### Changed

- Verified installation on a clean Laravel 13 application

---

## [0.1.0]

### Added

- Initial release
- DOCX document generation
- PDF conversion via LibreOffice
- Laravel integration
- PHPUnit test suite
- PHPStan static analysis
- Laravel Pint support
- GitHub Actions CI
