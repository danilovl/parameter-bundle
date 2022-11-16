<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle;

use Danilovl\ParameterBundle\DependencyInjection\ParameterExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ParameterBundle extends Bundle
{
    public function getContainerExtension(): ParameterExtension
    {
        return new ParameterExtension;
    }
}
