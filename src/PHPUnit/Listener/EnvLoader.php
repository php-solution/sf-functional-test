<?php

namespace PhpSolution\FunctionalTest\PHPUnit\Listener;

use Symfony\Component\Dotenv\Dotenv;
use PHPUnit\Framework\BaseTestListener;
use PHPUnit\Framework\TestSuite;

/**
 * Class EnvLoader
 */
class EnvLoader extends BaseTestListener
{
    /**
     * @var bool
     */
    private static $wasCalled = false;
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
        if (!self::$wasCalled) {
            self::$wasCalled = true;

            $dir = isset($GLOBALS['__PHPUNIT_CONFIGURATION_FILE']) ? dirname($GLOBALS['__PHPUNIT_CONFIGURATION_FILE']) : '';
            $paths = array_map(
                function (string $path) use ($dir) {
                    return realpath($dir . DIRECTORY_SEPARATOR . $path);
                },
                $this->paths
            );
            (new Dotenv())->load(...$paths);
        }
    }
}