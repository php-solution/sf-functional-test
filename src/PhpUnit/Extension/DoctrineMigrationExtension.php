<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\PhpUnit\Extension;

use PhpSolution\FunctionalTest\TestCase\ConsoleTestCase;
use PHPUnit\Runner\BeforeFirstTestHook;

class DoctrineMigrationExtension implements BeforeFirstTestHook
{
    public function executeBeforeFirstTest(): void
    {
        print 'DoctrineMigrationExtension:' . PHP_EOL;
        print ConsoleTestCase::runConsoleCommand('doctrine:migration:migrate', ['--no-interaction'])->fetch();
    }
}
