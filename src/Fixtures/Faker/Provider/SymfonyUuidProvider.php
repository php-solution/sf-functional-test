<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Fixtures\Faker\Provider;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

final class SymfonyUuidProvider
{
    public function uuidv4(string $uuid = null): AbstractUid
    {
        return $uuid ? Uuid::fromString($uuid) : new UuidV4();
    }

    public function randomUuidv4(array $array): ?AbstractUid
    {
        $uuid = $array[mt_rand(0, count($array) - 1)];

        return $uuid ? Uuid::fromString($uuid) : null;
    }
}
