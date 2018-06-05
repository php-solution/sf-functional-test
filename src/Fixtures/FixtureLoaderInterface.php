<?php
declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * FixtureLoaderInterface
 */
interface FixtureLoaderInterface
{
    /**
     * @param array       $files
     * @param string|null $objectManagerName
     *
     * @return array
     */
    public function load(array $files, ?string $objectManagerName): array;

    /**
     * @param string|null $objectManagerName
     *
     * @return ObjectManager
     */
    public function getObjectManager(?string $objectManagerName): ObjectManager;
}
