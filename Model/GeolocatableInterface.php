<?php

namespace TenEleven\Bundle\GeolocatableBundle\Model;

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

    /**
     * Get a string of address that Provider can use to geolocate
     * @return string Address
     */
    public function getGeolocatableAddress();
}