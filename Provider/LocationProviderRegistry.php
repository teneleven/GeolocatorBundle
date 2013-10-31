<?php


namespace Teneleven\Bundle\GeolocatorBundle\Provider;


class LocationProviderRegistry
{
    /**
     * @var array
     */
    protected $providers;

    public function __construct()
    {
        $this->providers = array();
    }

    /**
     * @return LocationProvider[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
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
     * @param $key
     * @return Boolean
     */
    public function hasProvider($key)
    {
        return isset($this->providers[$key]);
    }

    /**
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