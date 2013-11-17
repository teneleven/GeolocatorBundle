<?php

namespace Teneleven\Bundle\GeolocatorBundle\Form\DataTransformer;

use Geocoder\Exception\NoResultException;
use Geocoder\GeocoderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Transformer which turns a string into a geocoded address
 */
class StringToGeocodedTransformer implements DataTransformerInterface
{
    /**
     * @var GeocoderInterface
     */
    protected $geocoder;

    /**
     * Constructor
     *
     * @param GeocoderInterface $geocoder
     */
    public function __construct(GeocoderInterface $geocoder)
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
        } catch (NoResultException $e) {
            throw new TransformationFailedException(sprintf('%s could not be geolocated', $value));
        }

        return $result;
    }
}
