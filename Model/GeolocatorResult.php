<?php

namespace Teneleven\Bundle\GeolocatorBundle\Model;

/**
 * This class wraps a geolocator result along with the distance to the search area
 */
class GeolocatorResult
{
    /**
     * @var mixed
     */
    public $location;

    /**
     * @var float
     */
    public $distance;

    /**
     * @param mixed $location
     * @param float $distance
     */
    public function __construct($location, $distance)
    {
        $this->location = $location;
        $this->distance = $distance;
    }
}
