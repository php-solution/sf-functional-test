<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures\Faker\Provider;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UUIDProvider
{
    public function uuid4(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
