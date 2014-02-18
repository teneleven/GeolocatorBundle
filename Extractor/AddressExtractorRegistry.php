<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Extractor;

/**
 * Registry for address extractors
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class AddressExtractorRegistry
{
    /**
     * @var array
     */
    protected $extractors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->extractors = array();
    }

    /**
     * Register an extractor under the specified key
     *
     * @param string                    $key
     * @param AddressExtractorInterface $extractor
     */
    public function registerExtractor($key, AddressExtractorInterface $extractor)
    {
        $this->extractors[$key] = $extractor;
    }

    /**
     * Has extractor?
     *
     * @param  string  $key
     * @return Boolean
     */
    public function hasExtractor($key)
    {
        return isset($this->extractors[$key]);
    }

    /**
     * Get the extractor for the specified key
     *
     * @param  string                    $key
     * @return AddressExtractorInterface
     * @throws \InvalidArgumentException
     */
    public function getExtractor($key)
    {
        if (!$this->hasExtractor($key)) {
            throw new \InvalidArgumentException(sprintf('Address extractor with key "%s" does not exist', $key));
        }

        return $this->extractors[$key];
    }
}
