<?php

namespace Teneleven\Bundle\GeolocatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges configuration for this bundle
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('teneleven_geolocator');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('geocoder_service')->defaultValue('bazinga_geocoder.geocoder')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
