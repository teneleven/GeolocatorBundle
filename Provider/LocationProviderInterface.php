<?php


namespace Teneleven\Bundle\GeolocatorBundle\Provider;
use Geocoder\Result\ResultInterface;

/**
 * Interface implemented by classes that provide locations
 */
interface LocationProviderInterface
{
    /**
     * Get the form type used to filter this provider
     *
     * @return string
     */
    public function getFilterFormType();

    /**
     * Find locations based on criteria
     *
     * @param mixed $criteria
     * @return \Traversable
     */
    public function findLocations($criteria);

    /**
     * Get locations that need to be geocoded
     *
     * @return \Traversable
     */
    public function getUncodedLocations();

    /**
     * Get a geocodable address from object
     *
     * @param $object
     * @return string
     */
    public function extractAddress($object);

    /**
     * Update geocoordinates
     *
     * @param $object
     * @param ResultInterface $result
     */
    public function updateGeocoordinates($object, ResultInterface $result);
}