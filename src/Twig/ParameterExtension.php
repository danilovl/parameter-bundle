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

    public function get(string $key, string $delimiter = null, bool $ignoreNotFound = false): mixed
    {
        return $this->parameterService->get($key, $delimiter, $ignoreNotFound);
    }

    public function getString(string $key, string $delimiter = null): string
    {
        return $this->parameterService->getString($key, $delimiter);
    }

    public function getStringOrNull(string $key, string $delimiter = null): ?string
    {
        return $this->parameterService->getStringOrNull($key, $delimiter);
    }

    public function getInt(string $key, string $delimiter = null): int
    {
        return $this->parameterService->getInt($key, $delimiter);
    }

    public function getIntOrNull(string $key, string $delimiter = null): ?int
    {
        return $this->parameterService->getIntOrNull($key, $delimiter);
    }

    public function getFloat(string $key, string $delimiter = null): float
    {
        return $this->parameterService->getFloat($key, $delimiter);
    }

    public function getFloatOrNull(string $key, string $delimiter = null): ?float
    {
        return $this->parameterService->getFloatOrNull($key, $delimiter);
    }

    public function getBoolean(string $key, string $delimiter = null): bool
    {
        return $this->parameterService->getBoolean($key, $delimiter);
    }

    public function getBooleanOrNull(string $key, string $delimiter = null): ?bool
    {
        return $this->parameterService->getBooleanOrNull($key, $delimiter);
    }

    public function getArray(string $key, string $delimiter = null): array
    {
        return $this->parameterService->getArray($key, $delimiter);
    }

    public function getArrayOrNull(string $key, string $delimiter = null): ?array
    {
        return $this->parameterService->getArrayOrNull($key, $delimiter);
    }

    public function getUnitEnum(string $key, string $delimiter = null): UnitEnum
    {
        return $this->parameterService->getUnitEnum($key, $delimiter);
    }

    public function getUnitEnumOrNull(string $key, string $delimiter = null): ?UnitEnum
    {
        return $this->parameterService->getUnitEnumOrNull($key, $delimiter);
    }

    public function has(string $key, string $delimiter = null): bool
    {
        return $this->parameterService->has($key, $delimiter);
    }
}
