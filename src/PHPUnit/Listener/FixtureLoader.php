<?php
namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use PhpSolution\FunctionalTest\Command\FixturesLoadTruncateCommand;
use PhpSolution\FunctionalTest\Utils\CommandRunner;
use PHPUnit\Framework\BaseTestListener;
use PHPUnit\Framework\TestSuite;

/**
 * Class FixtureLoader
 *
 * @package PhpSolution\FunctionalTest\PHPUnit\Listener
 */
class FixtureLoader extends BaseTestListener
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
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite)
    {
        if (!self::$wasCalled) {
            self::$wasCalled = true;

            CommandRunner::runCommand(FixturesLoadTruncateCommand::NAME, $this->commandOptions, FixturesLoadTruncateCommand::class);
        }
    }
}