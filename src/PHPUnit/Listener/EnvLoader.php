<?php

namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use PHPUnit\Runner\TestHook;
use Symfony\Component\Dotenv\Dotenv;
use PHPUnit\Framework\TestSuite;

/**
 * EnvLoader
 */
class EnvLoader implements TestHook
{
    /**
     * @var bool
     */
    private $wasCalled = false;
    /**
     * @var array
     */
    private $paths = [];

    /**
     * EnvLoader constructor.
     *
     * @param array $paths
     */
    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
    }

    /**
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite): void
    {
        if ($this->wasCalled) {
            return;
        }
        $this->wasCalled = true;

        $dir = isset($GLOBALS['__PHPUNIT_CONFIGURATION_FILE']) ? dirname($GLOBALS['__PHPUNIT_CONFIGURATION_FILE']) : '';
        $paths = array_map(
            function (string $path) use ($dir) {
                return $dir . DIRECTORY_SEPARATOR . $path;
            },
            $this->paths
        );
        (new Dotenv())->load(...$paths);
    }
}