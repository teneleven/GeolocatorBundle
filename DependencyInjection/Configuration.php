<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validates and merges configuration for this bundle
 *
 * @author Daniel Richter <danny@1011i.com>
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
                ->arrayNode('locatables')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('repository')->defaultNull()->end()
                            ->integerNode('radius')->defaultNull()->end()
                            ->integerNode('limit')->defaultNull()->end()
                            ->variableNode('address_properties')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
