<?php

namespace Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass which registers address extractors
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
