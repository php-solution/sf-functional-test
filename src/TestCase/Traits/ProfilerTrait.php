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
    private static function getCollector(Profile|Profiler $profile, string $collectorName): object
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
    private static function withRequestProfiler(
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
    private static function loadProfiler(array $collectorNames = ['db', 'http_client', 'cache', 'memory']): Profiler
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
}
