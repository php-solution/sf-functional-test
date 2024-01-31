<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Subscriber;

use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;
use Symfony\Component\Console\Output\ConsoleOutput;

class PreRunNativeCommandLauncherSubscriber implements StartedSubscriber
{
    private string $command;

    private bool $exitOnError;

    public function __construct(string $command, bool $exitOnError = false)
    {
        $this->command = $command;
        $this->exitOnError = $exitOnError;
    }

    public function notify(Started $event): void
    {
        $output = new ConsoleOutput();
        $output->writeln(sprintf('<info>[PreRunNativeCommandLauncherSubscriber] Executing: %s</info>', $this->command));

        passthru($this->command, $code);

        if ($code > 0 && $this->exitOnError) {
            exit($code);
        }
    }
}
