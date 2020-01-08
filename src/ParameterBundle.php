<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle;

use Danilovl\ParameterBundle\DependencyInjection\ParameterExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ParameterBundle extends Bundle
{
    /**
     * @return ParameterExtension
     */
    public function getContainerExtension(): ParameterExtension
    {
        return new ParameterExtension;
    }
}
