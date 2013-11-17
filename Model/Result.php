<?php

namespace Teneleven\Bundle\GeolocatorBundle\Model;

/**
 * Result represents a found location of a geo-locator search.
 * Encapsulates the located object along with the distance to the search area.
 */
class Result
{
    /**
     * The located object
     *
     * @var object
     */
    public $location;

    /**
     * The distance from the search center
     *
     * @var float
     */
    public $distance;

    /**
     * The Javascript ID of the map marker that represents this result
     *
     * @var string
     */
    public $mapMarkerId;

    /**
     * The Javascript ID of the map popup window associated with this result
     *
     * @var string
     */
    public $mapWindowId;

    /**
     * Constructor
     *
     * @param object $location
     * @param float $distance
     */
    public function __construct($location, $distance)
    {
        $this->location = $location;
        $this->distance = $distance;
    }
}
