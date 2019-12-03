<?php

namespace PhpSolution\FunctionalTest\TestCase;

use PhpSolution\FunctionalTest\Assert\ResponseAsserter;
use PhpSolution\FunctionalTest\Tester\ApiTester;

/**
 * ApiTestCase
 */
class ApiTestCase extends AppTestCase
{
    use ResponseAsserter;

    /**
     * @param string $class
     *
     * @return ApiTester
     */
    protected static function createTester(string $class = ApiTester::class): ApiTester
    {
        if (static::$booted) {
            $client = static::$kernel->getContainer()->get('test.client');

            return new $class($client);
        }

        return new $class(static::createClient());
    }
}
