<?php

namespace Teneleven\Bundle\GeolocatorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
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
                'map' => array('width' => "100%", 'height' => "600px", 'auto_zoom' => true),
                'info_window' => array('auto_close' => true)
            )
        );

        foreach ($configs as $name => $config) {
            $container->prependExtensionConfig($name, $config);
        }
    }
}
