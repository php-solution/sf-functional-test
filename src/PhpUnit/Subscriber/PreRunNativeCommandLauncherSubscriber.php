<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Subscriber;

use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;

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
        passthru($this->command, $code);

        if ($code > 0 && $this->exitOnError) {
            exit($code);
        }
    }
}
