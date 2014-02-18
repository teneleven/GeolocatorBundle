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

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Teneleven\Bundle\GeolocatorBundle\Exception\AddressExtractionFailedException;

/**
 * AddressExtractor implementation which uses property accessor
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class AddressPropertyExtractor implements AddressExtractorInterface
{
    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * @var array
     */
    protected $addressProperties;

    /**
     * Constructor
     *
     * @param array $addressProperties
     */
    public function __construct(array $addressProperties)
    {
        $this->addressProperties = $addressProperties;
    }

    /**
     * Returns property accessor used in default address extraction
     *
     * @return PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if (!$this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function extractAddress($object)
    {
        $accessor = $this->getPropertyAccessor();
        $address = array();

        try {
            foreach ($this->addressProperties as $property) {
                $address[] = trim($accessor->getValue($object, $property));
            }
        } catch (\Exception $e) {
            throw new AddressExtractionFailedException(sprintf('Could not extract address (%s)', $e->getMessage()));
        }

        return implode(', ', $address);
    }
}
