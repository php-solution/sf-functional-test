<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Extension\Trait;

use PHPUnit\Runner\Extension\ParameterCollection;

trait ExitOnErrorAwareExtensionTrait
{
    public function exitOnError(ParameterCollection $parameters): bool
    {
        $exitOnError = false;
        if ($parameters->has('exitOnError')) {
            $exitOnError = filter_var($parameters->get('exitOnError'), FILTER_VALIDATE_BOOL);
        }

        return $exitOnError;
    }
}
