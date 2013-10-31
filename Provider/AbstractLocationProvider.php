<?php

namespace Teneleven\Bundle\GeolocatorBundle\Provider;

use Geocoder\Result\ResultInterface;
use Teneleven\Bundle\GeolocatorBundle\Model\GeoLocatableInterface;

abstract class AbstractLocationProvider implements LocationProviderInterface
{
    public function getFilterFormType()
    {
        return 'teneleven_geolocator_address_lookup';
    }

    public function updateGeocoordinates($object, ResultInterface $result)
    {
        if (!$object instanceof GeolocatableInterface) {
            throw new \InvalidArgumentException('Object must be instance of GeolocatableInterface.');
        }

        /* @var $object GeolocatableInterface */
        $object->setLatitude($result->getLatitude());
        $object->getLongitude($result->getLongitude());
    }
}