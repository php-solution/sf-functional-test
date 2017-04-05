<?php
namespace PhpSolution\FunctionalTest\Utils;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;

/**
 * Class ConsoleRunner
 *
 * @package PhpSolution\FunctionalTest\Utils
 */
class CommandRunner
{
    /**
     * @param string $name
     * @param array  $options
     * @param string $class
     */
    public static function runCommand(string $name, array $options, string $class)
    {
        $consoleApp = ConsoleTestCase::createConsoleApp();
        if (!$consoleApp->has($name)) {
            $consoleApp->add(new $class($name));
        }
        ConsoleTestCase::runConsoleCommand($name, $options, $consoleApp);
    }
}