<?php

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FixturesTrait
 */
trait FixturesTrait
{
    /**
     * @param array $files
     *
     * @return mixed
     */
    public function load(array $files)
    {
        $fixtureFiles = [];

        foreach ($files as $file) {
            $fixtureFiles[] = $this->doLocateFiles($file);
        }
        $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine_mongodb');

        return $loader->load($fixtureFiles);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function doLocateFiles(string $path): string
    {
        $path = sprintf('%s/%s', 'tests/DataFixtures', $path);
        $path = realpath($path);

        if (false === $path || false === file_exists($path)) {
            throw new \LogicException(sprintf("File %s does not exist", $path));
        }

        return $path;
    }

    /**
     * @return ContainerInterface
     */
    abstract function getContainer(): ContainerInterface;
}
