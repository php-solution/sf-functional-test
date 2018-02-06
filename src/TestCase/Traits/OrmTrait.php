<?php

namespace PhpSolution\FunctionalTest\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\AbstractManagerRegistry;

/**
 * OrmTrait
 */
trait OrmTrait
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
     * @param string $orderBy
     * @param array  $findBy
     *
     * @return object|null
     */
    protected function findEntity(string $entityClass, string $orderBy = 'id', array $findBy = [])
    {
        $repository = $this->getDoctrine()->getRepository($entityClass);
        $result = $repository->findBy($findBy, [$orderBy => 'DESC'], 1, 0);

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
