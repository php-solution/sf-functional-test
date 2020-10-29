<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Doctrine\Persistence\AbstractManagerRegistry;

trait DocumentTrait
{
    protected static function getDoctrine(): AbstractManagerRegistry
    {
        return self::getContainer()->get('doctrine_mongodb');
    }

    /**
     * This is more convenient alias for getting test document.
     *
     * @param string $documentClass
     * @param array  $criteria
     *
     * @return object|null
     */
    protected static function findDocument(string $documentClass, array $criteria = [])
    {
        $repository = self::getDoctrine()->getRepository($documentClass);

        return $repository->findOneBy($criteria);
    }

    /**
     * This is more convenient alias for getting test documents.
     *
     * @param string $documentClass
     * @param array  $criteria
     * @param array  $orderBy
     *
     * @return array
     */
    protected static function findDocuments(string $documentClass, array $criteria = [], array $orderBy = [])
    {
        $repository = self::getDoctrine()->getRepository($documentClass);

        return $repository->findBy($criteria, $orderBy);
    }

    /**
     * @param object $document
     *
     * @return object
     */
    protected static function refreshDocument(object $document): object
    {
        $em = self::getDoctrine()->getManager();
        $em->refresh($document);

        return $document;
    }

// Uncomment when php 8.0 will be enabled
//    abstract protected static function getContainer(): ContainerInterface;
}
