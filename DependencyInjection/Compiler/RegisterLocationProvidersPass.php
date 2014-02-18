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
 * Compiler pass which registers location providers
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class RegisterLocationProvidersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('teneleven.geolocator.providers')) {
            return;
        }

        $registry = $container->getDefinition('teneleven.geolocator.providers');

        foreach ($container->findTaggedServiceIds('teneleven.location_provider') as $id => $attributes) {
            $registry->addMethodCall('registerProvider', array($attributes[0]['alias'], new Reference($id)));
        }
    }
}
