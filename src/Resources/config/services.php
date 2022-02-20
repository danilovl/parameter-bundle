<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\ParameterBundle\Interfaces\ParameterServiceInterface;
use Danilovl\ParameterBundle\Service\ParameterService;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(ParameterService::class, ParameterService::class)
        ->public()
        ->autowire()
        ->alias(ParameterServiceInterface::class, ParameterService::class);
};
