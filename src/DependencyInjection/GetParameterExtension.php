<?php declare(strict_types=1);

namespace Danilovl\GetParameterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GetParameterExtension extends Extension
{
    public const ALIAS = 'danilovl_get_parameter';
    private const DIR_CONFIG = '/../Resources/config';

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . self::DIR_CONFIG));
        $loader->load('service.yaml');
        $loader->load('twig.yaml');
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return self::ALIAS;
    }
}
