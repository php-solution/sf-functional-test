<?php

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use PhpSolution\FunctionalTest\Fixtures\OdmFixtureLoader;
use PhpSolution\FunctionalTest\Fixtures\OrmFixtureLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FixturesTrait
 */
trait FixturesTrait
{
    /**
     * @param array       $files
     * @param null|string $entityManagerName
     *
     * @return array
     */
    protected function loadOrm(array $files, string $entityManagerName = null)
    {
        return (new OrmFixtureLoader($this->getContainer()))->load($files, $entityManagerName);
    }

    /**
     * @param array       $files
     * @param null|string $documentManagerName
     *
     * @return array
     */
    protected function loadOdm(array $files, string $documentManagerName = null)
    {
        return (new OdmFixtureLoader($this->getContainer()))->load($files, $documentManagerName);
    }

    /**
     * @param array       $files
     * @param string|null $type expected values: null, orm, odm
     * @param string|null $objectManagerName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function load(array $files, string $type = null, string $objectManagerName = null)
    {
        switch (true)
        {
            case 'odm' === $type:
            case $this->getContainer()->has('fidry_alice_data_fixtures.loader.doctrine_mongodb') && null === $type:
                return $this->loadOdm($files, $objectManagerName);
            case 'orm' === $type:
            case $this->getContainer()->has('fidry_alice_data_fixtures.loader.doctrine') && null === $type:
                return $this->loadOrm($files, $objectManagerName);
            default:
                throw new \Exception('Imposible situation, please check your doctrine configuration');
        }
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
    abstract protected function getContainer(): ContainerInterface;
}
