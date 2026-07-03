<?php

namespace Zaynasheff\DocumentGenerator;

final class DocumentFormat
{
    public const DOCX = 'docx';

    public const PDF = 'pdf';

    /**
     * @return array<string>
     */
    public static function all(): array
    {
        return [
            self::DOCX,
            self::PDF,
        ];
    }

    private function __construct()
    {
    }
}
