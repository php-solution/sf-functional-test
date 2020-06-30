<?php
declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures;

use Fidry\AliceDataFixtures\Bridge\Doctrine\Persister\ObjectManagerPersister;
use Fidry\AliceDataFixtures\Bridge\Doctrine\Purger\Purger;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use PhpSolution\FunctionalTest\Fixtures\Loader\CustomNativeLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AbstractFixtureLoader
 */
abstract class AbstractFixtureLoader implements FixtureLoaderInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array       $files
     * @param string|null $objectManagerName
     *
     * @return array
     */
    public function load(array $files, string $objectManagerName = null): array
    {
        $fixtureFiles = [];
        foreach ($files as $file) {
            $fixtureFiles[] = $this->locateFile($file);
        }

        $fixtures = (new CustomNativeLoader())
            ->loadFiles($fixtureFiles)
            ->getObjects();

        $om = $this->getObjectManager($objectManagerName);
        (new Purger($om))
            ->create(PurgeMode::createTruncateMode())
            ->purge();

        $persister = new ObjectManagerPersister($om);
        foreach ($fixtures as $fixture) {
            $persister->persist($fixture);
        }
        $persister->flush();
        $om->clear();

        return $fixtures;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function locateFile(string $path): string
    {
        $path = sprintf('%s/%s', 'tests/DataFixtures', $path);
        $realPath = realpath($path);

        if (false === $realPath) {
            throw new \RuntimeException(sprintf("File %s does not exist", $path));
        }

        return $realPath;
    }
}
