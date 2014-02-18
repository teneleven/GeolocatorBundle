<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Model;

/**
 * Interface implemented by classes that have geo-coordinates.
 *
 * @author Daniel Richter <danny@1011i.com>
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
