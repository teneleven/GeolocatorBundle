<?php

namespace Teneleven\Bundle\GeolocatorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler\RegisterAddressExtractorsPass;
use Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler\RegisterLocationProvidersPass;

class TenelevenGeolocatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterLocationProvidersPass());
        $container->addCompilerPass(new RegisterAddressExtractorsPass());
    }
}
