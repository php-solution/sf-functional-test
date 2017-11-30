<?php

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * ConsoleTestCase
 */
class ConsoleTestCase extends AppTestCase
{
    /**
     * @param string           $name
     * @param array            $options
     * @param Application|null $consoleApp
     *
     * @return int
     * @throws \Exception
     */
    public static function runConsoleCommand(string $name, array $options = [], Application $consoleApp = null): int
    {
        $consoleApp = is_null($consoleApp) ? self::createConsoleApp() : $consoleApp;
        $options['-e'] = isset($options['-e']) ? $options['-e'] : 'test';
        $options['-q'] = null;
        $options = array_merge($options, ['command' => $name]);
        $result = $consoleApp->run(new ArrayInput($options), new ConsoleOutput());

        $consoleApp->getKernel()->shutdown();

        return $result;
    }

    /**
     * @param array $kernelOptions
     * @param bool  $autoExit
     *
     * @return Application
     */
    public static function createConsoleApp(array $kernelOptions = [], bool $autoExit = false): Application
    {
        $kernel = static::createKernel($kernelOptions);
        $kernel->boot();
        $consoleApp = new Application($kernel);
        $consoleApp->setAutoExit($autoExit);

        return $consoleApp;
    }
}