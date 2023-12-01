<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\DependencyInjection;

use Danilovl\ParameterBundle\Service\ParameterService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ParameterExtension extends Extension
{
    private const string DIR_CONFIG = '/../Resources/config';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration;
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . self::DIR_CONFIG));
        $loader->load('services.yaml');
        $loader->load('twig.yaml');

        $parameterService = $container->getDefinition(ParameterService::class);
        $parameterService->setArgument('$delimiter', $config['delimiter']);

        $container->setParameter('danilovl_parameter.delimiter', $config['delimiter']);
    }

    public function getAlias(): string
    {
        return Configuration::ALIAS;
    }
}
