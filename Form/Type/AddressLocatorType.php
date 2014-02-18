<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form type which collects a location
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class AddressLocatorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('location', 'teneleven_geolocator_geocoded_address');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'teneleven_geolocator_address_locator';
    }
}
