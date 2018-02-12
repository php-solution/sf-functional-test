<?php

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * AppTestCase
 */
abstract class AppTestCase extends WebTestCase
{
    const DEFAULT_KERNEL_OPTS = ['environment' => 'test', 'debug' => false];

    /**
     * @var bool
     */
    protected static $kernelBooted = false;

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        static::$kernelBooted = false;

        parent::tearDown();
    }

    /**
     * @param array $options
     *
     * @return KernelInterface
     */
    protected static function bootKernel(array $options = self::DEFAULT_KERNEL_OPTS): KernelInterface
    {
        if (false === static::$kernelBooted) {
            static::$kernelBooted = true;

            parent::bootKernel($options);
        }

        return static::$kernel;
    }

    /**
     * @return string
     */
    protected static function getKernelClass(): string
    {
        return array_key_exists('KERNEL_CLASS', $_SERVER) ? $_SERVER['KERNEL_CLASS'] : parent::getKernelClass();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        static::bootKernel();

        return static::$kernel->getContainer();
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    protected function generateUrl(string $name, array $parameters = []): string
    {
        return $this->getContainer()->get('router')->generate($name, $parameters);
    }
}
