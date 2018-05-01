<?php

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FixturesTrait
 */
trait FixturesTrait
{
    /**
     * @param array       $files
     * @param string|null $type expected values: null, orm, odm
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function load(array $files, ?string $type = null)
    {
        $fixtureFiles = [];

        foreach ($files as $file) {
            $fixtureFiles[] = $this->doLocateFile($file);
        }

        $container = $this->getContainer();
        switch (true)
        {
            case 'odm' === $type:
            case $container->has('fidry_alice_data_fixtures.loader.doctrine_mongodb') && null === $type:
                $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine_mongodb');
                break;
            case 'orm' === $type:
            case $container->has('fidry_alice_data_fixtures.loader.doctrine') && null === $type:
                $loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
                break;
            default:
                throw new \Exception('Imposible situation, please check your doctrine configuration');
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
        $realPath = realpath($path);

        if (false === $realPath || false === file_exists($realPath)) {
            throw new \LogicException(sprintf("File %s does not exist", $path));
        }

        return $realPath;
    }

    /**
     * @return ContainerInterface
     */
    abstract function getContainer(): ContainerInterface;
}
