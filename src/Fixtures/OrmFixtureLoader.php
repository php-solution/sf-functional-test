<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class OrmFixtureLoader extends AbstractFixtureLoader
{
    /**
     * @param string|null $objectManagerName
     *
     * @return EntityManager
     */
    public function getObjectManager(?string $objectManagerName): ObjectManager
    {
        $prefix = null === $objectManagerName ? 'default' : $objectManagerName;

        return $this->container->get('doctrine.orm.'.$prefix.'_entity_manager');
    }
}
