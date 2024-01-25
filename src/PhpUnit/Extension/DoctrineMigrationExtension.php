<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Extension;

use PhpSolution\FunctionalTest\PhpUnit\Subscriber\PreRunCommandLauncherSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class DoctrineMigrationExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(new PreRunCommandLauncherSubscriber('doctrine:migration:migrate --no-interaction'));
    }
}
