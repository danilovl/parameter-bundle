<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Interfaces;

use UnitEnum;
use Danilovl\ParameterBundle\Exception\{
    EmptyParameterKeyException,
    ParameterNotFoundException
};

interface ParameterServiceInterface
{
    /**
     * @param string $key The parameter key (e.g., 'database.host')
     * @param string|null $delimiter Custom delimiter (default: '.')
     * @param bool $ignoreNotFound Return default instead of throwing exception
     * @param array|bool|string|int|float|UnitEnum|null $default Default value if parameter not found
     *
     * @return array|bool|string|int|float|UnitEnum|null The parameter value or default
     * @throws EmptyParameterKeyException If key is empty
     * @throws ParameterNotFoundException If parameter not found and ignoreNotFound is false
     */
    public function get(
        string $key,
        ?string $delimiter = null,
        bool $ignoreNotFound = false,
        array|bool|string|int|float|UnitEnum|null $default = null
    ): array|bool|string|int|float|UnitEnum|null;

    /**
     * @throws EmptyParameterKeyException
     * @throws ParameterNotFoundException
     */
    public function getString(string $key, ?string $delimiter = null, ?string $default = null): string;

    public function getStringOrNull(string $key, ?string $delimiter = null, ?string $default = null): ?string;

    /**
     * @throws EmptyParameterKeyException
     * @throws ParameterNotFoundException
     */
    public function getInt(string $key, ?string $delimiter = null, ?int $default = null): int;

    public function getIntOrNull(string $key, ?string $delimiter = null, ?int $default = null): ?int;

    /**
     * @throws EmptyParameterKeyException
     * @throws ParameterNotFoundException
     */
    public function getFloat(string $key, ?string $delimiter = null, ?float $default = null): float;

    public function getFloatOrNull(string $key, ?string $delimiter = null, ?float $default = null): ?float;

    /**
     * @throws EmptyParameterKeyException
     * @throws ParameterNotFoundException
     */
    public function getBoolean(string $key, ?string $delimiter = null, ?bool $default = null): bool;

    public function getBooleanOrNull(string $key, ?string $delimiter = null, ?bool $default = null): ?bool;

    /**
     * @throws EmptyParameterKeyException
     * @throws ParameterNotFoundException
     */
    public function getArray(string $key, ?string $delimiter = null, ?array $default = null): array;

    public function getArrayOrNull(string $key, ?string $delimiter = null, ?array $default = null): ?array;

    /**
     * @throws EmptyParameterKeyException
     * @throws ParameterNotFoundException
     */
    public function getUnitEnum(string $key, ?string $delimiter = null, ?UnitEnum $default = null): UnitEnum;

    public function getUnitEnumOrNull(string $key, ?string $delimiter = null, ?UnitEnum $default = null): ?UnitEnum;

    /**
     * Check if parameter exists.
     *
     * @throws EmptyParameterKeyException
     */
    public function has(string $key, ?string $delimiter = null): bool;
}
