<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleTestCase extends AppTestCase
{
    public static function runConsoleCommand(string $name, array $parameters = [], Application $consoleApp = null): BufferedOutput
    {
        $parameters = array_merge(['command' => $name], $parameters);
        $input = new ArrayInput($parameters);

        $bufferedOutput = new BufferedOutput();

        self::runCommand($input, $bufferedOutput, $consoleApp);

        return $bufferedOutput;
    }

    /**
     * @return array{int, OutputInterface}
     * @throws \Exception
     */
    public static function runCommand(
        InputInterface $input,
        OutputInterface $output,
        Application $consoleApp = null,
    ): array {
        $input = self::setDefaultOptions($input);

        $consoleApp ??= self::createConsoleApp();
        $output ??= new BufferedOutput();

        $exitCode = $consoleApp->run($input, $output);

        $consoleApp->getKernel()->shutdown();

        return [$exitCode, $output];
    }

    public static function createConsoleApp(array $kernelOptions = [], bool $autoExit = false): Application
    {
        $kernel = static::createKernel($kernelOptions);
        $kernel->boot();
        $consoleApp = new Application($kernel);
        $consoleApp->setAutoExit($autoExit);

        return $consoleApp;
    }

    private static function setDefaultOptions(InputInterface $input): InputInterface
    {
        $newCommand = (string) $input;
        $changed = false;

        if (!$input->hasParameterOption(['--quiet', '-q', '-v', '-vv', '-vvv'])) {
            $changed = true;
            $newCommand .= ' -v';
        }
        if (!$input->hasParameterOption(['-e', '--env'])) {
            $changed = true;
            $newCommand .= ' --env=test';
        }

        return $changed ? new StringInput($newCommand) : $input;
    }
}
