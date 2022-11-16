<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Interfaces;

interface ParameterServiceInterface
{
    public function get(string $key, string $delimiter = null, bool $ignoreNotFound = false): mixed;
    public function getString(string $key, string $delimiter = null): string;
    public function getInt(string $key, string $delimiter = null): int;
    public function getFloat(string $key, string $delimiter = null): float;
    public function getBoolean(string $key, string $delimiter = null): bool;
    public function getArray(string $key, string $delimiter = null): array;
    public function has(string $key, string $delimiter = null): bool;
}
