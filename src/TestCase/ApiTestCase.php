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
     * @return ApiTester
     */
    protected static function createTester(): ApiTester
    {
        return new ApiTester(static::createClient());
    }
}
