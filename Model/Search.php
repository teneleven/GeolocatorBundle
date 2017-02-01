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

use Geocoder\Model\Coordinates;

/**
 * Represents a geo-locator search.
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class Search
{
    /**
     * The center of the search
     *
     * @var Coordinates
     */
    protected $center;

    /**
     * The hits the search returned
     *
     * @var Result[]
     */
    protected $results = array();

    /**
     * Set the center
     *
     * @param Coordinates $center
     * @return $this
     */
    public function setCenter(Coordinates $center)
    {
        $this->center = $center;

        return $this;
    }

    /**
     * Get the center
     *
     * @return Coordinates
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * Add a result to this search result
     *
     * @param Result $result
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;
    }

    /**
     * Get results
     *
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Has results?
     *
     * @return Boolean
     */
    public function hasResults()
    {
        return count($this->results) > 0;
    }
}
