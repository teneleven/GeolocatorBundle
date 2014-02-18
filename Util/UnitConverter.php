<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Util;

/**
 * Helper class to convert between Kilometers and Miles
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class UnitConverter
{
    const KM_IN_MILES = 0.621371;

    /**
     * Convert KM to Miles
     *
     * @param  float $km
     * @return float
     */
    public static function kmToMiles($km)
    {
        return $km * self::KM_IN_MILES;
    }

    /**
     * Convert Miles to KM
     *
     * @param  float $miles
     * @return float
     */
    public static function milesToKm($miles)
    {
        return $miles / self::KM_IN_MILES;
    }
}
