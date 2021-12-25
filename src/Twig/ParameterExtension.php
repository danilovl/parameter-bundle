<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Twig;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class ParameterExtension extends AbstractExtension
{
    public function __construct(private ParameterServiceInterface $parameterService)
    {
    }

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

    public function get(string $key): mixed
    {
        return $this->parameterService->get($key);
    }

    public function getString(string $key): string
    {
        return $this->parameterService->get($key);
    }

    public function getInt(string $key): int
    {
        return $this->parameterService->get($key);
    }

    public function getFloat(string $key): float
    {
        return $this->parameterService->get($key);
    }

    public function getBoolean(string $key): bool
    {
        return $this->parameterService->get($key);
    }

    public function getArray(string $key): array
    {
        return $this->parameterService->get($key);
    }

    public function has(string $key): bool
    {
        return $this->parameterService->has($key);
    }
}