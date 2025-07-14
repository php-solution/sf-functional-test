<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Doctrine\Persistence\AbstractManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait DocumentTrait
{
    protected static function getDoctrine(): AbstractManagerRegistry
    {
        /** @var AbstractManagerRegistry $manager */
        $manager = self::getContainer()->get('doctrine_mongodb');

        return $manager;
    }

    /**
     * This is a more convenient alias for getting test document.
     *
     * @param array<string, mixed> $criteria
     */
    protected static function findDocument(string $documentClass, array $criteria = []): ?object
    {
        return self::getDoctrine()->getRepository($documentClass)->findOneBy($criteria);
    }

    /**
     * This is a more convenient alias for getting test documents.
     *
     * @param array<string, mixed> $criteria
     * @param array<string, int> $orderBy
     *
     * @return array<object>
     */
    protected static function findDocuments(string $documentClass, array $criteria = [], array $orderBy = []): array
    {
        return self::getDoctrine()->getRepository($documentClass)->findBy($criteria, $orderBy);
    }

    protected static function refreshDocument(object $document): object
    {
        $em = self::getDoctrine()->getManager();
        $em->refresh($document);

        return $document;
    }

    abstract protected static function getContainer(): ContainerInterface;
}
