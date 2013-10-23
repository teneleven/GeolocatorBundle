<?php

namespace Teneleven\Bundle\GeolocatorBundle\Model;

interface GeoLocatableInterface
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