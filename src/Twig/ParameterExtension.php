<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Twig;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class ParameterExtension extends AbstractExtension
{
    public function __construct(private readonly ParameterServiceInterface $parameterService) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('parameter_get', [$this, 'get']),
            new TwigFunction('parameter_get_string', [$this, 'getString']),
            new TwigFunction('parameter_get_int', [$this, 'getInt']),
            new TwigFunction('parameter_get_float', [$this, 'getFloat']),
            new TwigFunction('parameter_get_boolean', [$this, 'getBoolean']),
            new TwigFunction('parameter_get_array', [$this, 'getArray']),
            new TwigFunction('parameter_has', [$this, 'has'])
        ];
    }

    public function get(string $key, string $delimiter = null, bool $ignoreNotFound = false): mixed
    {
        return $this->parameterService->get($key, $delimiter, $ignoreNotFound);
    }

    public function getString(string $key, string $delimiter = null): string
    {
        return $this->parameterService->get($key, $delimiter);
    }

    public function getInt(string $key, string $delimiter = null): int
    {
        return $this->parameterService->get($key, $delimiter);
    }

    public function getFloat(string $key, string $delimiter = null): float
    {
        return $this->parameterService->get($key, $delimiter);
    }

    public function getBoolean(string $key, string $delimiter = null): bool
    {
        return $this->parameterService->get($key, $delimiter);
    }

    public function getArray(string $key, string $delimiter = null): array
    {
        return $this->parameterService->get($key, $delimiter);
    }

    public function has(string $key, string $delimiter = null): bool
    {
        return $this->parameterService->has($key, $delimiter);
    }
}