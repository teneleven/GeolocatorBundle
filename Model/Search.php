<?php

namespace Teneleven\Bundle\GeolocatorBundle\Model;

use Geocoder\Result\ResultInterface;

/**
 * Search represents a geo-locator search.
 */
class Search
{
    /**
     * The center of the search
     *
     * @var ResultInterface
     */
    protected $center;

    /**
     * The hits the search returned
     *
     * @var GeolocatorResult[]
     */
    protected $results = array();

    /**
     * Set the center
     *
     * @param ResultInterface $center
     * @return $this
     */
    public function setCenter(ResultInterface $center)
    {
        $this->center = $center;

        return $this;
    }

    /**
     * Get the center
     *
     * @return ResultInterface
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
