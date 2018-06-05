<?php
declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * OdmFixtureLoader
 */
class OdmFixtureLoader extends AbstractFixtureLoader implements FixtureLoaderInterface
{
    /**
     * @param string|null $objectManagerName
     *
     * @return DocumentManager
     */
    public function getObjectManager(?string $objectManagerName): ObjectManager
    {
        $prefix = null === $objectManagerName ? '' : $objectManagerName . '_';

        return $this->container->get('doctrine_mongodb.odm.'.$prefix.'document_manager');
    }
}
