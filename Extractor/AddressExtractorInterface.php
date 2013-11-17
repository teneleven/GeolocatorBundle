<?php

namespace Teneleven\Bundle\GeolocatorBundle\Extractor;

/**
 * Interface implemented by address extractors
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
