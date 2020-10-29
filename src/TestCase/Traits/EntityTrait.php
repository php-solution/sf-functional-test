<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Doctrine\Persistence\AbstractManagerRegistry;

trait EntityTrait
{
    /**
     * @return AbstractManagerRegistry|object
     */
    protected static function getDoctrine(): AbstractManagerRegistry
    {
        return self::getContainer()->get('doctrine');
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
    protected static function findEntity(string $entityClass, array $findBy = [], array $orderBy = [])
    {
        $repository = self::getDoctrine()->getRepository($entityClass);
        $result = $repository->findBy($findBy, $orderBy, 1, 0);

        return count($result) > 0 ? $result[0] : null;
    }

    /**
     * @param object $entity
     *
     * @return object
     */
    protected static function refreshEntity(object $entity): object
    {
        $em = self::getDoctrine()->getManager();
        $em->refresh($entity);

        return $entity;
    }

// Uncomment when php 8.0 will be enabled
//    abstract protected static function getContainer(): ContainerInterface;
}
