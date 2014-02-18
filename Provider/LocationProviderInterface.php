<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Provider;

use Symfony\Component\Form\Form;
use Teneleven\Bundle\GeolocatorBundle\Model\Search;

/**
 * Interface implemented by classes that provide locations
 *
 * @author Daniel Richter <danny@1011i.com>
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
     * @param  Form   $form
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
