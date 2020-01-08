<?php declare(strict_types=1);

namespace Danilovl\GetParameterBundle;

use Danilovl\GetParameterBundle\Twig\GetParameterExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GetParameterBundle extends Bundle
{
    /**
     * @return GetParameterExtension
     */
    public function getContainerExtension(): GetParameterExtension
    {
        return new GetParameterExtension;
    }
}
