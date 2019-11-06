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
    protected static function bootKernel(array $options = []): KernelInterface
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
    protected static function getContainer(): ContainerInterface
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
    protected static function generateUrl(string $name, array $parameters = []): string
    {
        return self::getContainer()->get('router')->generate($name, $parameters);
    }

    /**
     * @param array $subset
     * @param array $set
     */
    public static function assertSubset(array $subset, array $set): void
    {
        foreach ($subset as $key => $value) {
            self::assertArrayHasKey($key, $set);
            if (is_array($value)) {
                self::assertIsArray($set[$key]);
                self::assertSubset($value, $set[$key]);
            } else {
                self::assertEquals($value, $set[$key]);
            }
        }
    }
}
