<?php

namespace PhpSolution\FunctionalTest\TestCase;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * AppTestCase
 */
class AppTestCase extends WebTestCase
{
    const DEFAULT_KERNEL_OPTS = ['environment' => 'test', 'debug' => false];

    /**
     * @var bool
     */
    protected static $kernelBooted = false;

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        static::$kernelBooted = false;

        parent::tearDown();
    }

    /**
     * @param array $options
     *
     * @return KernelInterface
     */
    protected static function bootKernel(array $options = self::DEFAULT_KERNEL_OPTS): KernelInterface
    {
        if (false === static::$kernelBooted) {
            static::$kernelBooted = true;

            parent::bootKernel($options);
        }

        return static::$kernel;
    }

    /**
     * @return string
     */
    protected static function getKernelClass(): string
    {
        return array_key_exists('KERNEL_CLASS', $_SERVER) ? $_SERVER['KERNEL_CLASS'] : parent::getKernelClass();
    }

    /**
     * @param array $server
     * @param array $kernelOptions
     *
     * @return Client
     */
    protected function getClient(array $server = [], array $kernelOptions = self::DEFAULT_KERNEL_OPTS): Client
    {
        return static::createClient($kernelOptions, $server);
    }

    /**
     * Return Client with authorized token user
     *
     * @param string $login
     * @param string $pass
     *
     * @return Client
     */
    protected function getAuthorizedClient(string $login, string $pass): Client
    {
        return $this->getClient(['PHP_AUTH_USER' => $login, 'PHP_AUTH_PW' => $pass]);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        static::bootKernel();

        return static::$kernel->getContainer();
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter(): RouterInterface
    {
        return $this->getContainer()->get('router');
    }

    /**
     * @param string $route
     * @param array  $params
     * @param int    $referenceType
     *
     * @return string
     */
    protected function generateUrl(string $route, array $params = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->getRouter()->generate($route, $params, $referenceType);
    }

    /**
     * @return null|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    protected function getSecurityToken()
    {
        return $this->getContainer()->get('security.token_storage')->getToken();
    }

    /**
     * @return \Doctrine\Common\Persistence\AbstractManagerRegistry
     */
    protected function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * This is more convenient alias for getting test entity.
     *
     * @param string $entityClass
     * @param string $orderBy
     * @param array  $findBy
     *
     * @return object|null
     */
    protected function findEntity(string $entityClass, string $orderBy = 'id', array $findBy = [])
    {
        $repository = $this->getDoctrine()->getRepository($entityClass);
        $result = $repository->findBy($findBy, [$orderBy => 'DESC'], 1, 0);

        return count($result) > 0 ? $result[0] : null;
    }

    /**
     * @param object $entity
     *
     * @return object
     */
    protected function refreshEntity($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->refresh($entity);

        return $entity;
    }
}