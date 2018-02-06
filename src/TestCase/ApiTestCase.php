<?php

namespace PhpSolution\FunctionalTest\TestCase;

use PhpSolution\FunctionalTest\Tester\ApiTester;

/**
 * ApiTestCase
 */
class ApiTestCase extends AppTestCase
{
    /**
     * @return ApiTester
     */
    protected static function createTester(): ApiTester
    {
        return new ApiTester(static::createClient());
    }
}
