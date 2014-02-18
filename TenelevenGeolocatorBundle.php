<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler\RegisterAddressExtractorsPass;
use Teneleven\Bundle\GeolocatorBundle\DependencyInjection\Compiler\RegisterLocationProvidersPass;

/**
 * TenelevenGeolocatorBundle
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class TenelevenGeolocatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterLocationProvidersPass());
        $container->addCompilerPass(new RegisterAddressExtractorsPass());
    }
}
