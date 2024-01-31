<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Subscriber;

use PhpCsFixer\Console\Output\ErrorOutput;
use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $input = new StringInput($this->command);

        $output = new ConsoleOutput();
        $output->writeln(sprintf('<info>[PreRunCommandLauncherSubscriber] Executing: %s</info>', $input));

        try {
            [$exitCode] = ConsoleTestCase::runCommand($input, $output);
        } catch (\Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $output->writeln(sprintf('<comment>%s</comment>', $e->getTraceAsString()));

            $exitCode = 1;
        }

        if ($this->exitOnError && $exitCode > 0) {
            exit($exitCode);
        }

        $output->writeln('<info>[PreRunCommandLauncherSubscriber] Pre-run command has been completed.</info>');
    }
}
