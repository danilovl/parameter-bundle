<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Interfaces;

interface ParameterServiceInterface
{
    public function get(string $key, bool $ignoreNotFound = false): mixed;
    public function getString(string $key): string;
    public function getInt(string $key): int;
    public function getFloat(string $key): float;
    public function getBoolean(string $key): bool;
    public function getArray(string $key): array;
    public function has(string $key): bool;
}
