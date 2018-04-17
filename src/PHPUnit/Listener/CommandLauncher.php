<?php

namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

/**
 * CommandLauncher
 */
class CommandLauncher implements TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * @var bool
     */
    private $wasCalled = false;

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $options;

    /**
     * @param string $command
     * @param array  $options
     */
    public function __construct($command, array $options = [])
    {
        $this->command = $command;
        $this->options = $options;
    }

    /**
     * @param TestSuite $suite
     *
     * @throws \Exception
     */
    public function startTestSuite(TestSuite $suite): void
    {
        if ($this->wasCalled) {
            return;
        }
        $this->wasCalled = true;

        // By default, set output verbosity - quiet
        if (0 === count(array_intersect(array_keys($this->options), ['-q', '-v', '--v', '---v']))) {
            $this->options['-q'] = true;
        }

        print ConsoleTestCase::runConsoleCommand($this->command, $this->options)->fetch();
    }
}
