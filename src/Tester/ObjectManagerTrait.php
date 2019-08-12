<?php
declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Tester;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * ObjectManagerTrait
 */
trait ObjectManagerTrait
{
    /**
     * @var ObjectManager[]
     */
    private $objectManagers = [];

    /**
     * @return ObjectManager[]
     */
    public function getObjectManagers(): array
    {
        return $this->objectManagers;
    }

    /**
     * @param string $objectManagerName
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     * @throws ServiceNotFoundException
     */
    public function addObjectManager(string $objectManagerName): self
    {
        $om = $this->client->getContainer()->get($objectManagerName);
        if (!$om instanceof ObjectManager) {
            throw new \InvalidArgumentException(sprintf('Instance of ObjectManager expected, but got "%s"', get_class($om)));
        }
        $this->objectManagers[] = $om;

        return $this;
    }

    /**
     * @return static
     */
    public function resetObjectManagers(): self
    {
        $this->objectManagers = [];

        return $this;
    }

    /**
     * Try to find usual object managers
     */
    private function guessObjectManagers(): void
    {
        $possibleServices = [
            'doctrine.orm.default_entity_manager',
            'doctrine_mongodb.odm.document_manager',
        ];
        foreach ($possibleServices as $possibleService) {
            try {
                $this->addObjectManager($possibleService);
            } catch (ServiceNotFoundException|\InvalidArgumentException $ex) {
            }
        }
    }

    final private function clearObjectManagers(): void
    {
        foreach ($this->getObjectManagers() as $objectManager) {
            $objectManager->clear();
        }
    }
}
