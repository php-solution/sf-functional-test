<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Extension;

use PhpSolution\FunctionalTest\PhpUnit\Extension\Trait\ExitOnErrorAwareExtensionTrait;
use PhpSolution\FunctionalTest\PhpUnit\Subscriber\PreRunCommandLauncherSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class PreRunCommandLauncherExtension implements Extension
{
    use ExitOnErrorAwareExtensionTrait;

    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters
    ): void {
        if (!$parameters->has('command')) {
            echo '[PreRunCommandLauncherExtension] No pre-run command present.' . PHP_EOL;
            return;
        }

        $facade->registerSubscriber(
            new PreRunCommandLauncherSubscriber(
                $parameters->get('command'),
                $this->exitOnError($parameters)
            )
        );
    }
}
