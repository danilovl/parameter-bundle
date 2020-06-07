<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Twig;

use Danilovl\ParameterBundle\Services\ParameterService;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class ParameterExtension extends AbstractExtension
{
    private ParameterService $parameterService;

    public function __construct(ParameterService $parameterService)
    {
        $this->parameterService = $parameterService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('parameter_get', [$this, 'get']),
            new TwigFunction('parameter_has', [$this, 'has'])
        ];
    }

    public function get(string $key)
    {
        return $this->parameterService->get($key);
    }

    public function has(string $key): bool
    {
        return $this->parameterService->has($key);
    }
}