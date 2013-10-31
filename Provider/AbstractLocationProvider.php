<?php

namespace Teneleven\Bundle\GeolocatorBundle\Provider;

use Geocoder\Result\ResultInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Teneleven\Bundle\GeolocatorBundle\Exception\AddressExtractionFailedException;
use Teneleven\Bundle\GeolocatorBundle\Model\GeoLocatableInterface;

/**
 * AbstractLocationProvider
 *
 * Provides default implementations for easy extension
 */
abstract class AbstractLocationProvider implements LocationProviderInterface
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * Returns property accessor used in default address extraction
     *
     * @return \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if (!$this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }

    /**
     * Returns the fields used for default address extraction
     *
     * @return array
     */
    protected function getAddressFields()
    {
        return array('address', 'city', 'state', 'postalcode', 'country');
    }

    /**
     * Default implementation which specifies a simple address lookup form
     *
     * {@inheritdoc}
     */
    public function getFilterFormType()
    {
        return 'teneleven_geolocator_address_lookup';
    }

    /**
     * Default implementation which assumes object implements GeolocatableInterface
     *
     * {@inheritdoc}
     */
    public function updateGeocoordinates($object, ResultInterface $result)
    {
        if (!$object instanceof GeolocatableInterface) {
            throw new \InvalidArgumentException('Object must be instance of GeolocatableInterface.');
        }

        $object->setLatitude($result->getLatitude());
        $object->setLongitude($result->getLongitude());
    }

    /**
     * Default implementation which extracts address via property accessor
     *
     * {@inheritdoc}
     */
    public function extractAddress($object)
    {
        $accessor = $this->getPropertyAccessor();
        $address = array();

        try {
            foreach ($this->getAddressFields() as $field) {
                $address[] = $accessor->getValue($object, $field);
            }
        } catch (\Exception $e) {
            throw new AddressExtractionFailedException(sprintf('Could not extract address (%s)', $e->getMessage()));
        }

        return implode(', ', $address);
    }
}
