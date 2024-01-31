<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ConsoleTestCase extends AppTestCase
{
    public static function runConsoleCommand(ArgvInput $input, Application $consoleApp = null, bool $autoExit = false): BufferedOutput
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
