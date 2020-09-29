<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures\Faker\Provider;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UUIDProvider
{
    public function uuid4(string $uuid = null): UuidInterface
    {
        return $uuid ? Uuid::fromString($uuid) : Uuid::uuid4();
    }

    public function randomUuid4(array $array): ?UuidInterface
    {
        $uuid = $array[mt_rand(0, count($array) - 1)];

        return $uuid ? Uuid::fromString($uuid) : null;
    }
}
