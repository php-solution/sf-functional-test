<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase;

use PhpSolution\FunctionalTest\Assert\ResponseAsserter;
use PhpSolution\FunctionalTest\Tester\ApiTester;

class ApiTestCase extends AppTestCase
{
    use ResponseAsserter;

    protected static function createTester(string $class = ApiTester::class): ApiTester
    {
        if (static::$booted) {
            $client = static::$kernel->getContainer()->get('test.client');

            return new $class($client);
        }

        return new $class(static::createClient());
    }
}
