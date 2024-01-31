<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Subscriber;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;

class PreRunCommandLauncherSubscriber implements StartedSubscriber
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

        $app = ConsoleTestCase::createConsoleApp([], $this->exitOnError);

        echo '[PreRunCommandLauncherSubscriber] Executing: ' . $input . PHP_EOL;

        $output = new BufferedOutput();

        try {
            $res = ConsoleTestCase::runCommand($input, $output, $app, $this->exitOnError);
        } catch (\Throwable $e) {
            $app->renderThrowable($e, $output);

            if ($this->exitOnError) {
                exit(1);
            }

            echo '[PreRunCommandLauncherSubscriber] Pre-run command has failed.' . PHP_EOL;

            return;
        }

        echo $res->fetch();

        echo '[PreRunCommandLauncherSubscriber] Pre-run command has been completed.' . PHP_EOL;
    }
}
