<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\ParameterBundle\Twig\ParameterExtension;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('danilovl.twig.parameter', ParameterExtension::class)
        ->args([
            service('danilovl.parameter'),
        ])
        ->public()
        ->tag('twig.extension')
        ->alias(ParameterExtension::class, 'danilovl.twig.parameter');
};