<?php


namespace Teneleven\Bundle\GeolocatorBundle\Extractor;


class PropertyPathAddressExtractor
{
    protected $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }
}