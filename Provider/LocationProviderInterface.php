<?php

namespace Teneleven\Bundle\GeolocatorBundle\Provider;

use Symfony\Component\Form\Form;
use Teneleven\Bundle\GeolocatorBundle\Model\Search;

/**
 * Interface implemented by classes that provide locations
 */
interface LocationProviderInterface
{
    /**
     * Get the form type used to filter this provider
     *
     * @return string
     */
    public function getFilterFormType();

    /**
     * Find locations based on criteria
     *
     * @param Form $form
     * @return Search
     */
    public function findLocations(Form $form);

    /**
     * Get locations that need to be geo-coded
     *
     * @return array
     */
    public function getUncodedLocations();
}
