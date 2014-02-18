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
 * Interface implemented by address extractors
 *
 * @author Daniel Richter <danny@1011i.com>
 */
interface AddressExtractorInterface
{
    /**
     * Extract an address string from object, for use in geo-coding.
     *
     * @param $object
     * @return string
     */
    public function extractAddress($object);
}
