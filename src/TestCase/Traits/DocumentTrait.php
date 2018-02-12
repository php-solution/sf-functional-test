<?php

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\AbstractManagerRegistry;

/**
 * DocumentTrait
 */
trait DocumentTrait
{
    /**
     * @return AbstractManagerRegistry|object
     */
    protected function getDoctrine(): AbstractManagerRegistry
    {
        return $this->getContainer()->get('doctrine_mongodb');
    }

    /**
     * This is more convenient alias for getting test document.
     *
     * @param string $documentClass
     * @param array  $criteria
     *
     * @return object|null
     */
    protected function findDocument(string $documentClass, array $criteria = [])
    {
        $repository = $this->getDoctrine()->getRepository($documentClass);

        return $repository->findOneBy($criteria);
    }

    /**
     * This is more convenient alias for getting test documents.
     *
     * @param string $documentClass
     * @param array  $criteria
     * @param array  $orderBy
     *
     * @return object|null
     */
    protected function findDocuments(string $documentClass, array $criteria = [], array $orderBy = [])
    {
        $repository = $this->getDoctrine()->getRepository($documentClass);

        return $repository->findBy($criteria, $orderBy);
    }

    /**
     * @param object $document
     *
     * @return object
     */
    protected function refreshDocument($document)
    {
        $em = $this->getDoctrine()->getManager();
        $em->refresh($document);

        return $document;
    }

    /**
     * @return ContainerInterface
     */
    abstract function getContainer(): ContainerInterface;
}
