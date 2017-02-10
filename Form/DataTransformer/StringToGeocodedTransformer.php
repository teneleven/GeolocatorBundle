<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Form\DataTransformer;

use Geocoder\Exception\NoResult;
use Geocoder\Geocoder;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Transformer which turns a string into a geocoded address
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class StringToGeocodedTransformer implements DataTransformerInterface
{
    /**
     * @var Geocoder
     */
    protected $geocoder;

    /**
     * Constructor
     *
     * @param Geocoder $geocoder
     */
    public function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return ''; //@todo implement way to convert a geo result to a string?
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        try {
            $result = $this->geocoder->geocode($value);
        } catch (NoResult $e) {
            throw new TransformationFailedException(sprintf('%s could not be geolocated', $value));
        }

        if (!$firstResult = $result->first()) {
            return null;
        }

        return $firstResult->getCoordinates();
    }
}
