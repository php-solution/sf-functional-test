<?php
namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;

/**
 * Class MigrationLauncher
 *
 * @package PhpSolution\FunctionalTest\PHPUnit\Listener
 */
class MigrationLauncher extends \PHPUnit_Framework_BaseTestListener
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

            $commandName = 'doctrine:migrations:migrate';
            $consoleApp = ConsoleTestCase::createConsoleApp();
            if (!$consoleApp->has($commandName)) {
                $consoleApp->add(new LoadDataFixturesDoctrineCommand($commandName));
            }
            ConsoleTestCase::runConsoleCommand($commandName, $this->commandOptions, $consoleApp);
        }
    }
}