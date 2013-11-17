<?php

namespace Teneleven\Bundle\GeolocatorBundle\Model;

/**
 * Interface implemented by classes that can have geo-coordinates.
 */
interface GeolocatableInterface
{
    /**
     * Get Latitude.
     *
     * @return float
     */
    public function getLatitude();

    /**
     * Set Latitude.
     */
    public function setLatitude($latitude);

    /**
     * Get Longitude.
     *
     * @return float
     */
    public function getLongitude();

    /**
     * Set Longitude.
     */
    public function setLongitude($longitude);
}
