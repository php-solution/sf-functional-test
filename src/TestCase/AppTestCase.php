<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase;

use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AppTestCase extends WebTestCase
{
    use ProphecyTrait;

    protected static bool $kernelBooted = false;

    protected function tearDown(): void
    {
        static::$kernelBooted = false;

        parent::tearDown();
    }

    protected static function bootKernel(array $options = []): KernelInterface
    {
        if (false === static::$kernelBooted) {
            static::$kernelBooted = true;

            parent::bootKernel($options);
        }

        return static::$kernel;
    }

    protected static function getKernelClass(): string
    {
        return array_key_exists('KERNEL_CLASS', $_SERVER) ? $_SERVER['KERNEL_CLASS'] : parent::getKernelClass();
    }

    protected static function generateUrl(string $name, array $parameters = []): string
    {
        return self::getContainer()->get('router')->generate($name, $parameters);
    }

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
