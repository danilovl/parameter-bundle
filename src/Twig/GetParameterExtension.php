<?php declare(strict_types=1);

namespace Danilovl\GetParameterBundle\Twig;

use Danilovl\GetParameterBundle\Services\GetParameterService;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class GetParameterExtension extends AbstractExtension
{
    /**
     * @var GetParameterService
     */
    private $getParameterService;

    /**
     * @param GetParameterService $getParameterService
     */
    public function __construct(GetParameterService $getParameterService)
    {
        $this->getParameterService = $getParameterService;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_parameter', [$this, 'getParameter'])
        ];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getParameter(string $key)
    {
        return $this->getParameterService->getParameter($key);
    }
}