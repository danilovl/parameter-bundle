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
            new TwigFunction('parameter_has', [$this, 'has'])
        ];
    }

    public function get(string $key): mixed
    {
        return $this->parameterService->get($key);
    }

    public function has(string $key): bool
    {
        return $this->parameterService->has($key);
    }
}