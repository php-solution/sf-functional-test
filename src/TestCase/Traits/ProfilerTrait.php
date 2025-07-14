<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use PhpSolution\FunctionalTest\Response\ResponseWrapper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\Profiler;

trait ProfilerTrait
{
    /**
     * @throws \InvalidArgumentException
     */
    protected static function assertDoctrineQueriesCount(int $expectedCount, Profile|Profiler $profile): void
    {
        self::assertSame(
            $expectedCount,
            self::getDoctrineCollector($profile)->getQueryCount(),
            'Too many database queries',
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected static function assertDoctrineQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertLessThanOrEqual(
            $expectedCount,
            self::getDoctrineCollector($profile)->getQueryCount(),
            'Too many database queries',
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected static function assertDoctrineSelectQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        $queries = self::getDoctrineCollector($profile)->getQueries();
        $cnt = 0;
        foreach ($queries['default'] as $query) {
            if (str_starts_with(strtolower($query['sql']), 'SELECT')) {
                ++$cnt;
            }
        }
        self::assertLessThanOrEqual($expectedCount, $cnt, 'Too many database queries');
    }

    /**
     * @return DoctrineDataCollector
     *
     * @throws \InvalidArgumentException
     */
    protected static function getDoctrineCollector(Profile|Profiler $profile): object
    {
        return self::getCollector($profile, 'db');
    }

    protected static function getCollector(Profile|Profiler $profile, string $collectorName): object
    {
        /** @var DoctrineDataCollector $collector */
        $collector = $profile instanceof Profile
            ? $profile->getCollector($collectorName)
            : $profile->get($collectorName);

        return $collector;
    }

    /**
     * @param callable $callback Must return {@see Response}
     * @param array<string> $collectorNames
     *
     * @return array{ResponseWrapper, Profile}
     */
    protected static function withRequestProfiler(
        callable $callback,
        array $collectorNames = ['db', 'http_client', 'cache', 'memory'],
    ): array {
        $profiler = self::loadProfiler($collectorNames);

        $response = $callback();

        $profiler->disable();

        $profile = $profiler->loadProfileFromResponse($response->getResponse());

        return [$response, $profile];
    }

    /**
     * @param array<string> $collectors
     */
    protected static function loadProfiler(array $collectorNames = ['db', 'http_client', 'cache', 'memory']): Profiler
    {
        /** @var Profiler $profiler */
        $profiler = static::getContainer()->get('profiler');
        if (!$profiler) {
            throw new \RuntimeException('Profiler service is not available.');
        }

        /** @var array<DataCollectorInterface> $collectors */
        $handlers = [];

        foreach ($collectorNames as $collector) {
            if ($profiler->has($collector)) {
                $handlers[] = $profiler->get($collector);
            }
        }

        $profiler->set($handlers);
        $profiler->reset();
        $profiler->enable();

        return $profiler;
    }

    abstract private static function getContainer(): ContainerInterface;
    abstract private static function assertSame(mixed $expected, mixed $actual, string $msg): void;
    abstract private static function assertLessThanOrEqual(int|float $expected, int|float $actual, string $msg): void;
}
