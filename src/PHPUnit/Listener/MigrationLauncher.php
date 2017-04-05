<?php
namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use PhpSolution\FunctionalTest\Utils\CommandRunner;
use PHPUnit\Framework\BaseTestListener;
use PHPUnit\Framework\TestSuite;

/**
 * Class MigrationLauncher
 *
 * @package PhpSolution\FunctionalTest\PHPUnit\Listener
 */
class MigrationLauncher extends BaseTestListener
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
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite)
    {
        if (!self::$wasCalled) {
            self::$wasCalled = true;

            CommandRunner::runCommand('doctrine:migrations:migrate', $this->commandOptions, LoadDataFixturesDoctrineCommand::class);
        }
    }
}