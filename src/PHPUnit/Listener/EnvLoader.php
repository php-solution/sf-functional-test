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
        (new Dotenv())->load(...$this->paths);
    }
}