<?php

namespace Teneleven\Bundle\GeolocatorBundle\Form\DataTransformer;

use Geocoder\Exception\NoResultException;
use Geocoder\GeocoderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class StringToGeocodedTransformer implements DataTransformerInterface
{
    protected $geocoder;

    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        return ''; //@todo implement better way
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