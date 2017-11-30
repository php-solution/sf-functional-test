<?php

namespace PhpSolution\FunctionalTest\Utils;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use Symfony\Component\Console\Command\Command;

/**
 * ConsoleRunner
 */
class CommandRunner
{
    /**
     * @param string $class
     * @param array  $options
     *
     * @throws \Exception
     */
    public static function runCommand(string $class, array $options): void
    {
        $consoleApp = ConsoleTestCase::createConsoleApp();
        $command = self::createCommand($class);
        $name = $command->getName();
        if (!$consoleApp->has($name)) {
            $consoleApp->add($command);
        }
        ConsoleTestCase::runConsoleCommand($name, $options, $consoleApp);
    }

    /**
     * @param string $class
     *
     * @return Command
     */
    private static function createCommand(string $class): Command
    {
        return new $class();
    }
}