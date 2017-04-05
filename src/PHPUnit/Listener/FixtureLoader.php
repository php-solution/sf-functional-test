<?php
namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use PhpSolution\FunctionalTest\Command\FixturesLoadTruncateCommand;
use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;

/**
 * Class FixtureLoader
 *
 * @package PhpSolution\FunctionalTest\PHPUnit\Listener
 */
class FixtureLoader extends \PHPUnit_Framework_BaseTestListener
{
    /**
     * @var bool
     */
    private static $wasCalled = false;
    /**
     * @var array
     */
    private $commandOptions = [];

    /**
     * FixtureLoader constructor.
     *
     * @param array $commandOptions
     */
    public function __construct(array $commandOptions = [])
    {
        $this->commandOptions = $commandOptions;
    }

    /**
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        if (!self::$wasCalled) {
            self::$wasCalled = true;

            $commandName = FixturesLoadTruncateCommand::NAME;
            $consoleApp = ConsoleTestCase::createConsoleApp();
            if (!$consoleApp->has($commandName)) {
                $consoleApp->add(new FixturesLoadTruncateCommand($commandName));
            }
            ConsoleTestCase::runConsoleCommand($commandName, $this->commandOptions, $consoleApp);
        }
    }
}