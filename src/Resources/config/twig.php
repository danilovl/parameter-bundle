<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\ParameterBundle\Twig\ParameterExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(ParameterExtension::class, ParameterExtension::class)
        ->private()
        ->autowire()
        ->tag('twig.extension');
};
