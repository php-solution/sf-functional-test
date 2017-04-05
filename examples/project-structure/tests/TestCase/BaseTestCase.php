<?php
namespace Tests\TestCase;

use PhpSolution\FunctionalTest\TestCase\AppTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class BaseTestCase
 *
 * @package Tests\TestCase
 */
class BaseTestCase extends AppTestCase
{
    const USER_LOGIN = 'user@email.com';
    const USER_PASS = 'test';

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAuthUserClient(): Client
    {
        return $this->getAuthorizedClient(self::USER_LOGIN, self::USER_LOGIN);
    }
}