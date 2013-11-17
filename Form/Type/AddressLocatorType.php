<?php

namespace Teneleven\Bundle\GeolocatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
