<?php


namespace Teneleven\Bundle\GeolocatorBundle\Extractor;


interface AddressExtractorInterface
{
    public function extractAddress($data);
}