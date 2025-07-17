<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Doctrine\Persistence\AbstractManagerRegistry;

trait EntityTrait
{
    /**
     * @return AbstractManagerRegistry
     */
    protected static function getDoctrine(): AbstractManagerRegistry
    {
        /** @var AbstractManagerRegistry $manager */
        $manager = self::getContainer()->get('doctrine');

        return $manager;
    }

    /**
     * This is a more convenient alias for getting test entity.
     *
     * @template T of object
     *
     * @param class-string<T> $entityClass
     * @param array<string, mixed> $findBy
     * @param array<string, int> $orderBy
     *
     * @return T|null
     */
    protected static function findEntity(string $entityClass, array $findBy = [], array $orderBy = []): ?object
    {
        $repository = self::getDoctrine()->getRepository($entityClass);
        $result = $repository->findBy($findBy, $orderBy, 1, 0);

        return count($result) > 0 ? $result[0] : null;
    }

    protected static function refreshEntity(object $entity): object
    {
        $em = self::getDoctrine()->getManager();
        $em->refresh($entity);

        return $entity;
    }
}
