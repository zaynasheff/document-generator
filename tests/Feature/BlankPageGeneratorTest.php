<?php

namespace Zaynasheff\DocumentGenerator\Tests\Feature;

use Zaynasheff\DocumentGenerator\Generators\BlankPageGenerator;
use Zaynasheff\DocumentGenerator\Tests\TestCase;

class BlankPageGeneratorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $output = __DIR__.'/../Fixtures/output';

        if (! is_dir($output)) {
            mkdir($output, 0777, true);
        }

        foreach (glob($output.'/*') ?: [] as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function test_can_generate_blank_pdf(): void
    {
        $generator = new BlankPageGenerator;

        $destination = __DIR__
            .'/../Fixtures/output/blank.pdf';

        $result = $generator->generate(
            $destination
        );

        $this->assertSame(
            $destination,
            $result
        );

        $this->assertFileExists(
            $destination
        );

        $this->assertGreaterThan(
            0,
            filesize($destination)
        );
    }

    public function test_can_generate_temporary_blank_pdf(): void
    {
        $generator = new BlankPageGenerator;

        $file = $generator->temporary();

        $this->assertFileExists(
            $file
        );

        $this->assertGreaterThan(
            0,
            filesize($file)
        );

        unlink($file);
    }
}
