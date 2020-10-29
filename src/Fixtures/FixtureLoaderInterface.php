<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures;

use Doctrine\Persistence\ObjectManager;

interface FixtureLoaderInterface
{
    public function load(array $files, ?string $objectManagerName): array;

    public function getObjectManager(?string $objectManagerName): ObjectManager;
}
