<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\HttpKernel\Profiler\Profiler;

trait EntityProfilerTrait
{
    /**
     * @throws \InvalidArgumentException
     */
    private static function assertDoctrineQueriesCount(int $expectedCount, Profile|Profiler $profile): void
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
    private static function assertDoctrineQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertLessThanOrEqual(
            $expectedCount,
            self::getDoctrineCollector($profile)->getQueryCount(),
            'Too many database queries',
        );
    }

    private static function assertDoctrineSelectQueriesCount(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void
    {
        self::assertSame(
            $expectedCount,
            self::getSelectQueriesCount($profile),
            'Too many database select queries',
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    private static function assertDoctrineSelectQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertLessThanOrEqual(
            $expectedCount,
            self::getSelectQueriesCount($profile),
            'Too many database select queries'
        );
    }

    private static function getSelectQueriesCount(Profile|Profiler $profile): int
    {
        return self::getQueriesByMask($profile, 'SELECT');
    }

    private static function assertDoctrineUpdateQueriesCount(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertSame(
            $expectedCount,
            self::getUpdateQueriesCount($profile),
            'Too many database update queries'
        );
    }

    private static function assertDoctrineUpdateQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertLessThanOrEqual(
            $expectedCount,
            self::getUpdateQueriesCount($profile),
            'Too many database update queries'
        );
    }

    private static function getUpdateQueriesCount(Profile|Profiler $profile): int
    {
        return self::getQueriesByMask($profile, 'UPDATE');
    }

    private static function assertDoctrineInsertQueriesCount(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertSame(
            $expectedCount,
            self::getInsertQueriesCount($profile),
            'Too many database insert queries'
        );
    }

    private static function assertDoctrineInsertQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertLessThanOrEqual(
            $expectedCount,
            self::getInsertQueriesCount($profile),
            'Too many database insert queries'
        );
    }

    private static function getInsertQueriesCount(Profile|Profiler $profile): int
    {
        return self::getQueriesByMask($profile, 'INSERT');
    }

    private static function assertDoctrineDeleteQueriesCount(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertSame(
            $expectedCount,
            self::getDeleteQueriesCount($profile),
            'Too many database delete queries'
        );
    }

    private static function assertDoctrineDeleteQueriesCountLessThanOrEqual(
        int $expectedCount,
        Profile|Profiler $profile,
    ): void {
        self::assertLessThanOrEqual(
            $expectedCount,
            self::getDeleteQueriesCount($profile),
            'Too many database delete queries'
        );
    }

    private static function getDeleteQueriesCount(Profile|Profiler $profile): int
    {
        return self::getQueriesByMask($profile, 'DELETE');
    }

    private static function getQueriesByMask(Profile|Profiler $profile, string $mask): int
    {
        $queries = self::getDoctrineCollector($profile)->getQueries();
        $cnt = 0;

        foreach ($queries['default'] as $query) {
            if (str_starts_with(strtolower($query['sql']), $mask)) {
                ++$cnt;
            }
        }

        return $cnt;
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

    abstract private static function assertSame(mixed $expected, mixed $actual, string $msg): void;
    abstract private static function assertLessThanOrEqual(int|float $expected, int|float $actual, string $msg): void;
    abstract private static function getCollector(Profile|Profiler $profile, string $collectorName): object;
}
