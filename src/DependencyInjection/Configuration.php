<?php

declare(strict_types=1);

namespace Tuscanicz\DateTimeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Tuscanicz\DateTimeBundle\DateTimeFactory;

/**
 * This is the class that validates and merges configuration from your app/config files
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tuscanicz_datetime');

        $rootNode
            ->children()
                ->scalarNode('app_timezone')
                    ->defaultValue(DateTimeFactory::TIMEZONE_GMT)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
