<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Service\ParameterService;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('danilovl.parameter', ParameterService::class)
        ->args([
            service('parameter_bag')
        ])
        ->public()
        ->alias(ParameterServiceInterface::class, 'danilovl.parameter');
};
