<?php

namespace Teneleven\Bundle\GeolocatorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler\RegisterLocationProvidersPass;
use Teneleven\Bundle\GeolocatorBundle\DependencyInjection\TenelevenGeolocatorExtension;

class TenelevenGeolocatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterLocationProvidersPass());
    }
}
