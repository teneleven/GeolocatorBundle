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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This class configures the Teneleven Geolocator bundle
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class TenelevenGeolocatorExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setAlias('teneleven.geolocator.geocoder', $config['geocoder_service']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config['locatables'] as $alias => $locatableConfig) {
            $this->configureLocatable($alias, $locatableConfig, $container);
        }
    }

    protected function configureLocatable($alias, array $config, ContainerBuilder $container)
    {
        $providerServiceId = 'teneleven.geolocator.location_provider.'.$alias;

        if ($config['repository'] && !$container->hasDefinition($providerServiceId)) {
            $locationProvider = new Definition('%teneleven.geolocator.location_provider.class%');
            $locationProvider
                ->setArguments(array(new Reference($config['repository']), $config['radius'], $config['limit']))
                ->addTag('teneleven.location_provider', array('alias' => $alias))
            ;
            $container->setDefinition($providerServiceId, $locationProvider);
        }

        if ($config['address_properties']) {
            $extractor = new Definition('%teneleven.geolocator.address_extractor.property.class%');
            $extractor
                ->setArguments(array($config['address_properties']))
                ->addTag('teneleven.address_extractor', array('alias' => $alias))
            ;
            $container->setDefinition('teneleven.geolocator.address_extractor.'.$alias, $extractor);
        }
    }

    public function getAlias()
    {
        return 'teneleven_geolocator';
    }

    /**
     * Configure sensitive defaults for other bundles
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = array(
            'bazinga_geocoder' => array(
                'providers' => array('google_maps' => null)
            ),
            'ivory_google_map' => array(
                'map' => array('width' => "100%", 'height' => "600px"),
                'info_window' => array('auto_close' => true)
            ),
            'doctrine' => array(
                'orm' => array(
                    'dql' => array(
                        'numeric_functions' => array(
                            'GEO_DISTANCE' => 'Craue\GeoBundle\Doctrine\Query\Mysql\GeoDistance'
                        )
                    )
                )
            )
        );

        foreach ($configs as $name => $config) {
            $container->prependExtensionConfig($name, $config);
        }
    }
}
