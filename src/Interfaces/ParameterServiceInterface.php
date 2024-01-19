<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Interfaces;

use UnitEnum;

interface ParameterServiceInterface
{
    public function get(
        string $key,
        string $delimiter = null,
        bool $ignoreNotFound = false
    ): array|bool|string|int|float|UnitEnum|null;

    public function getString(string $key, string $delimiter = null): string;
    public function getInt(string $key, string $delimiter = null): int;
    public function getFloat(string $key, string $delimiter = null): float;
    public function getBoolean(string $key, string $delimiter = null): bool;
    public function getArray(string $key, string $delimiter = null): array;
    public function getUnitEnum(string $key, string $delimiter = null): UnitEnum;
    public function has(string $key, string $delimiter = null): bool;
}
