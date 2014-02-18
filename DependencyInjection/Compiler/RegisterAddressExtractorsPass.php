<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass which registers address extractors
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class RegisterAddressExtractorsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('teneleven.geolocator.address_extractors')) {
            return;
        }

        $registry = $container->getDefinition('teneleven.geolocator.address_extractors');

        foreach ($container->findTaggedServiceIds('teneleven.address_extractor') as $id => $attributes) {
            $registry->addMethodCall('registerExtractor', array($attributes[0]['alias'], new Reference($id)));
        }
    }
}
