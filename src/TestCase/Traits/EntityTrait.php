<?php

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\AbstractManagerRegistry;

/**
 * EntityTrait
 */
trait EntityTrait
{
    /**
     * @return AbstractManagerRegistry|object
     */
    protected function getDoctrine(): AbstractManagerRegistry
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * This is more convenient alias for getting test entity.
     *
     * @param string $entityClass
     * @param array  $findBy
     * @param array  $orderBy
     *
     * @return object|null
     */
    protected function findEntity(string $entityClass, array $findBy = [], array $orderBy = [])
    {
        $repository = $this->getDoctrine()->getRepository($entityClass);
        $result = $repository->findBy($findBy, $orderBy, 1, 0);

        return count($result) > 0 ? $result[0] : null;
    }

    /**
     * @param object $entity
     *
     * @return object
     */
    protected function refreshEntity($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->refresh($entity);

        return $entity;
    }

    /**
     * @return ContainerInterface
     */
    abstract function getContainer(): ContainerInterface;
}
