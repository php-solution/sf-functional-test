<?php

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

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
     * @return BufferedOutput
     * @throws \Exception
     */
    public static function runConsoleCommand(string $name, array $options = [], Application $consoleApp = null): BufferedOutput
    {
        $consoleApp = is_null($consoleApp) ? self::createConsoleApp() : $consoleApp;
        $options['-e'] = isset($options['-e']) ? $options['-e'] : 'test';
        // By default, set output verbosity - verbose
        if (0 === count(array_intersect(array_keys($options), ['-q', '-v', '--v', '---v']))) {
            $options['-v'] = true;
        }
        $options = array_merge($options, ['command' => $name]);
        $output = new BufferedOutput();
        $consoleApp->run(new ArrayInput($options), $output);

        $consoleApp->getKernel()->shutdown();

        return $output;
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
