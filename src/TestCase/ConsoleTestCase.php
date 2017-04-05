<?php
namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * Class ConsoleTestCase
 *
 * @package PhpSolution\FunctionalTest\TestCase
 */
class ConsoleTestCase extends AppTestCase
{
    /**
     * @param string                  $commandName
     * @param array                   $commandOptions
     * @param Application|null $consoleApp
     *
     * @return int
     */
    public static function runConsoleCommand(string $commandName, array $commandOptions = [], Application $consoleApp = null): int
    {
        $consoleApp = is_null($consoleApp) ? self::createConsoleApp() : $consoleApp;
        $commandOptions['-e'] = isset($commandOptions['-e']) ? $commandOptions['-e'] : 'test';
        $commandOptions['-q'] = null;
        $commandOptions = array_merge($commandOptions, ['command' => $commandName]);
        $result = $consoleApp->run(new ArrayInput($commandOptions));

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