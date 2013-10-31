<?php

namespace Teneleven\Bundle\GeolocatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Teneleven\Bundle\GeolocatorBundle\Model\GeoLocatableInterface;
use Teneleven\Bundle\GeolocatorBundle\Provider\LocationProviderInterface;

class GeolocatorController extends Controller
{
    /**
     * @param string $entity the entity key that the provider is registered under
     * @param Request $request
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function locate($entity, Request $request, $template = 'TenelevenGeolocatorBundle::results.html.twig')
    {
        $locations = array();
        $provider = $this->getLocationProvider($entity);
        $form = $this->createForm($provider->getFilterFormType(), null, array('method' => 'GET'));

        if ($form->handleRequest($request)->isValid()) {
            $criteria = $form->getData();
            $locations = $provider->findLocations($criteria);
        }

        return $this->renderLocations($template, $locations, $form);
    }

    /**
     * Helper method to render the response
     *
     * @param $template
     * @param $locations
     * @param Form $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLocations($template, $locations, Form $form)
    {
        $jsIds = array('markers' => array(), 'windows' => array());

        return $this->render($template, array(
            'form' => $form->createView(),
            'locations' => $locations,
            'searchLocation' => $form->isSubmitted() && $form->isValid() ? $form->getData() : null,
            'map' => $this->buildMap($template, $locations, $jsIds),
            'jsIds' => $jsIds
        ));
    }

    protected function buildMap($template, $locations, &$jsIds)
    {
        /* @var $map \Ivory\GoogleMap\Map */
        $map = $this->get('ivory_google_map.map');

        $twigTemplate = $this->get('twig')->loadTemplate($template);

        $counter = 0;
        foreach ($locations as $location) {

            if (!$location instanceof GeolocatableInterface) {
                continue;
            }

            //hack, these should not even be here
            if (!$location->getLatitude() || !$location->getLongitude()) {
                continue;
            }

            /* @var $marker \Ivory\GoogleMap\Overlays\Marker */
            $marker = $this->get('ivory_google_map.marker');
            $marker->setPosition($location->getLatitude(), $location->getLongitude());

            if ($twigTemplate->hasBlock('teneleven_geolocator_item_window')) {

                /* @var $infoWindow \Ivory\GoogleMap\Overlays\InfoWindow */
                $infoWindow = $this->get('ivory_google_map.info_window');
                $infoWindow->setContent($twigTemplate->renderBlock(
                    'teneleven_geolocator_item_window',
                    array('location' => $location)
                ));

                $marker->setInfoWindow($infoWindow);
                $jsIds['windows'][$location->getId()] = $infoWindow->getJavascriptVariable();
            }

            $jsIds['markers'][$location->getId()] = $marker->getJavascriptVariable();

            $map->addMarker($marker);

            $counter++;
        }

        return $map;
    }

    /**
     * @param string $entity
     * @return LocationProviderInterface
     */
    public function getLocationProvider($entity)
    {
        $providers = $this->get('teneleven.geolocator.providers');

        return $providers->getProvider($entity);
    }
}
