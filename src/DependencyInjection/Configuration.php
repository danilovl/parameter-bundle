<?php declare(strict_types=1);

namespace Danilovl\ParameterBundle\DependencyInjection;

use Danilovl\ParameterBundle\Service\ParameterService;
use Symfony\Component\Config\Definition\Builder\{
    TreeBuilder,
    NodeParentInterface
};
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const string ALIAS = 'danilovl_parameter';

    public function getConfigTreeBuilder(): NodeParentInterface
    {
        $treeBuilder = new TreeBuilder(self::ALIAS);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('delimiter')
                    ->defaultValue(ParameterService::DEFAULT_DELIMITER)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
