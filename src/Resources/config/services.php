<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\ParameterBundle\Services\ParameterService;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('danilovl.parameter', ParameterService::class)
        ->args([
            service('service_container'),
        ])
        ->public()
        ->alias(ParameterService::class, 'danilovl.parameter');
};