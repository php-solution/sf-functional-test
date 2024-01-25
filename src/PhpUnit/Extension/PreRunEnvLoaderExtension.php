<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Extension;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Symfony\Component\Dotenv\Dotenv;

class PreRunEnvLoaderExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if (!$parameters->has('paths')) {
            echo '[PreRunEnvLoaderExtension] No paths present.' . PHP_EOL;
            return;
        }

        $paths = explode(',', $parameters->get('paths'));

        $dir = isset($GLOBALS['__PHPUNIT_CONFIGURATION_FILE']) ? dirname($GLOBALS['__PHPUNIT_CONFIGURATION_FILE']) : '';
        $paths = array_map(
            static fn(string $path): string => $dir . DIRECTORY_SEPARATOR . $path,
            $paths
        );
        (new Dotenv())->load(...$paths);
    }
}
