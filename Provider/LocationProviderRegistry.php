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

/**
 * Registry for LocationProviders
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class LocationProviderRegistry
{
    /**
     * @var LocationProviderInterface[]
     */
    protected $providers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->providers = array();
    }

    /**
     * @return LocationProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Register a provider
     *
     * @param $key
     * @param LocationProviderInterface $provider
     */
    public function registerProvider($key, LocationProviderInterface $provider)
    {
        if (!$this->hasProvider($key)) {
            $this->providers[$key] = $provider;
        }
    }

    /**
     * Is provider registered?
     *
     * @param $key
     * @return Boolean
     */
    public function hasProvider($key)
    {
        return isset($this->providers[$key]);
    }

    /**
     * Get specified provider
     *
     * @param $key
     * @return LocationProviderInterface
     * @throws \InvalidArgumentException
     */
    public function getProvider($key)
    {
        if (!$this->hasProvider($key)) {
            throw new \InvalidArgumentException(sprintf('Location Provider with key "%s" does not exist', $key));
        }

        return $this->providers[$key];
    }
}
