<?php

namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use PhpSolution\FunctionalTest\Utils\CommandRunner;
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
    private $class;
    /**
     * @var array
     */
    private $options = [];

    /**
     * @param string $class
     * @param array  $options
     */
    public function __construct($class, array $options = [])
    {
        $this->class = $class;
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

        CommandRunner::runCommand($this->class, $this->options);
    }
}