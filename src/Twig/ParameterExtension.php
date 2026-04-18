<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Twig;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use UnitEnum;

class ParameterExtension extends AbstractExtension
{
    public function __construct(private readonly ParameterServiceInterface $parameterService) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('parameter_get', $this->get(...)),
            new TwigFunction('parameter_get_string', $this->getString(...)),
            new TwigFunction('parameter_get_string_or_null', $this->getStringOrNull(...)),
            new TwigFunction('parameter_get_int', $this->getInt(...)),
            new TwigFunction('parameter_get_int_or_null', $this->getIntOrNull(...)),
            new TwigFunction('parameter_get_float', $this->getFloat(...)),
            new TwigFunction('parameter_get_float_or_null', $this->getFloatOrNull(...)),
            new TwigFunction('parameter_get_boolean', $this->getBoolean(...)),
            new TwigFunction('parameter_get_boolean_or_null', $this->getBooleanOrNull(...)),
            new TwigFunction('parameter_get_array', $this->getArray(...)),
            new TwigFunction('parameter_get_array_or_null', $this->getArrayOrNull(...)),
            new TwigFunction('parameter_get_unit_enum', $this->getUnitEnum(...)),
            new TwigFunction('parameter_get_unit_enum_or_null', $this->getUnitEnumOrNull(...)),
            new TwigFunction('parameter_has', $this->has(...))
        ];
    }

    public function get(
        string $key,
        ?string $delimiter = null,
        bool $ignoreNotFound = false,
        array|bool|string|int|float|UnitEnum|null $default = null
    ): array|bool|string|int|float|UnitEnum|null {
        return $this->parameterService->get(
            key: $key,
            delimiter: $delimiter,
            ignoreNotFound: $ignoreNotFound,
            default: $default
        );
    }

    public function getString(string $key, ?string $delimiter = null, ?string $default = null): string
    {
        return $this->parameterService->getString(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getStringOrNull(string $key, ?string $delimiter = null, ?string $default = null): ?string
    {
        return $this->parameterService->getStringOrNull(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getInt(string $key, ?string $delimiter = null, ?int $default = null): int
    {
        return $this->parameterService->getInt(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getIntOrNull(string $key, ?string $delimiter = null, ?int $default = null): ?int
    {
        return $this->parameterService->getIntOrNull(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getFloat(string $key, ?string $delimiter = null, ?float $default = null): float
    {
        return $this->parameterService->getFloat(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getFloatOrNull(string $key, ?string $delimiter = null, ?float $default = null): ?float
    {
        return $this->parameterService->getFloatOrNull(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getBoolean(string $key, ?string $delimiter = null, ?bool $default = null): bool
    {
        return $this->parameterService->getBoolean(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getBooleanOrNull(string $key, ?string $delimiter = null, ?bool $default = null): ?bool
    {
        return $this->parameterService->getBooleanOrNull(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getArray(string $key, ?string $delimiter = null, ?array $default = null): array
    {
        return $this->parameterService->getArray(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getArrayOrNull(string $key, ?string $delimiter = null, ?array $default = null): ?array
    {
        return $this->parameterService->getArrayOrNull(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getUnitEnum(string $key, ?string $delimiter = null, ?UnitEnum $default = null): UnitEnum
    {
        return $this->parameterService->getUnitEnum(key: $key, delimiter: $delimiter, default: $default);
    }

    public function getUnitEnumOrNull(string $key, ?string $delimiter = null, ?UnitEnum $default = null): ?UnitEnum
    {
        return $this->parameterService->getUnitEnumOrNull(key: $key, delimiter: $delimiter, default: $default);
    }

    public function has(string $key, ?string $delimiter = null): bool
    {
        return $this->parameterService->has(key: $key, delimiter: $delimiter);
    }
}
