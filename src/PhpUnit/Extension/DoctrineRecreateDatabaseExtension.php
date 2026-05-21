<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Extension;

use PhpSolution\FunctionalTest\PhpUnit\Extension\Trait\ExitOnErrorAwareExtensionTrait;
use PhpSolution\FunctionalTest\PhpUnit\Subscriber\PreRunCommandLauncherSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class DoctrineRecreateDatabaseExtension implements Extension
{
    use ExitOnErrorAwareExtensionTrait;

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(
            new PreRunCommandLauncherSubscriber(
                'doctrine:database:drop --if-exists --force',
                $this->exitOnError($parameters)
            )
        );
        $facade->registerSubscriber(
            new PreRunCommandLauncherSubscriber(
                'doctrine:database:create --if-not-exists',
                $this->exitOnError($parameters)
            )
        );
    }
}
