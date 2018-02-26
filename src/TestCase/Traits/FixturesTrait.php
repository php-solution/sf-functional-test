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
    protected function load(array $files)
    {
        $fixtureFiles = [];

        foreach ($files as $file) {
            $fixtureFiles[] = $this->doLocateFile($file);
        }

        $container = $this->getContainer();
        if ($container->has('fidry_alice_data_fixtures.loader.doctrine_mongodb')) {
            $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine_mongodb');
        } else {
            $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        }

        return $loader->load($fixtureFiles);
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function getFixturesFromJson(string $path): array
    {
        return json_decode(file_get_contents($this->doLocateFile($path)), true);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function doLocateFile(string $path): string
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
