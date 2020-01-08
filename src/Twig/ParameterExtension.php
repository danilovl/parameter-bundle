<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\Twig;

use Danilovl\ParameterBundle\Services\ParameterService;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class ParameterExtension extends AbstractExtension
{
    /**
     * @var ParameterService
     */
    private $parameterService;

    /**
     * @param ParameterService $parameterService
     */
    public function __construct(ParameterService $parameterService)
    {
        $this->parameterService = $parameterService;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('parameter_get', [$this, 'get']),
            new TwigFunction('parameter_has', [$this, 'has'])
        ];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->parameterService->get($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function has(string $key): bool
    {
        return $this->parameterService->has($key);
    }
}