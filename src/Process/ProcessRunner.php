<?php

namespace Zaynasheff\DocumentGenerator\Process;

use RuntimeException;

final class ProcessRunner
{
    /**
     * Executes a shell command.
     *
     * @throws RuntimeException
     */
    public function run(string $command): void
    {
        exec(
            $command . ' 2>&1',
            $output,
            $exitCode
        );

        if ($exitCode !== 0) {
            throw new RuntimeException(
                implode(PHP_EOL, $output)
            );
        }
    }
}