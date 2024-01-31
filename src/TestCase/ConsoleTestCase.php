<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;

class ConsoleTestCase extends AppTestCase
{
    public static function runConsoleCommand(string $name, array $parameters = [], Application $consoleApp = null): BufferedOutput
    {
        $parameters['-e'] = $parameters['-e'] ?? 'test';

        // By default, set output verbosity - verbose
        if (0 === count(array_intersect(array_keys($parameters), ['-q', '-v', '-vv', '-vvv']))) {
            $parameters['-v'] = true;
        }
        $parameters = array_merge(['command' => $name], $parameters);
        $input = new ArrayInput($parameters);

        return self::runCommand($input, $consoleApp);
    }

    public static function runCommand(InputInterface $input, Application $consoleApp = null, bool $autoExit = false): BufferedOutput
    {
        $consoleApp ??= self::createConsoleApp([], $autoExit);

        $output = new BufferedOutput();
        $consoleApp->run($input, $output);

        $consoleApp->getKernel()->shutdown();

        return $output;
    }

    public static function createConsoleApp(array $kernelOptions = [], bool $autoExit = false): Application
    {
        $kernel = static::createKernel($kernelOptions);
        $kernel->boot();
        $consoleApp = new Application($kernel);
        $consoleApp->setAutoExit($autoExit);

        return $consoleApp;
    }
}
