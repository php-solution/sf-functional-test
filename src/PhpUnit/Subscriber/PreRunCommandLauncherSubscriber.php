<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Subscriber;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;
use Symfony\Component\Console\Input\StringInput;

class PreRunCommandLauncherSubscriber implements StartedSubscriber
{
    private string $command;
    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function notify(Started $event): void
    {
        $newCommand = $this->command;

        $input = new StringInput($this->command);
        if (!$input->hasParameterOption(['--quiet', '-q', '-v', '-vv', '-vvv'])) {
            $newCommand .= ' -q';
        }
        if (!$input->hasParameterOption(['-e', '--env'])) {
            $newCommand .= ' --env=test';
        }

        if ($this->command !== $newCommand) {
            $input = new StringInput($newCommand);
        }

        print '[PreRunCommandLauncherSubscriber] Executing: ' . $input . PHP_EOL;

        print ConsoleTestCase::runConsoleCommand($input)->fetch();

        print '[PreRunCommandLauncherSubscriber] Pre-run command has been completed.' . PHP_EOL;
    }
}
