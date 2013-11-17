<?php

namespace Teneleven\Bundle\GeolocatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geocoder\Exception\QuotaExceededException;
use Symfony\Component\HttpFoundation\Request;
use Teneleven\Bundle\GeolocatorBundle\Model\GeolocatableInterface;
use Teneleven\Bundle\GeolocatorBundle\Model\Result;
use Teneleven\Bundle\GeolocatorBundle\Model\Search;
use Teneleven\Bundle\GeolocatorBundle\Provider\LocationProviderInterface;

/**
 * Geolocator Controller
 */
class GeolocatorController extends Controller
{
    /**
     * Displays a geo-locator screen with map, form, and locations
     *
     * @param string $entity   The entity key that the provider is registered under
     * @param Request $request
     * @param string $template The template to render
     *
     * @return Response
     */
    public function locate($entity, Request $request, $template = 'TenelevenGeolocatorBundle::results.html.twig')
    {
        $provider = $this->getLocationProvider($entity);
        $form = $this->get('form.factory')->createNamed('', $provider->getFilterFormType(), null, array('method' => 'GET', 'csrf_protection' => false));

        try {
            $form->handleRequest($request);
        } catch (QuotaExceededException $e) {
            $this->get('logger')->error($e->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Sorry, this locator has exceeded the quota for location look-ups. Please try again at a later time.');
        }

        if (!$form->isValid()) {
            return $this->render($template, array(
                'map' => $this->getMap(),
                'form' => $form->createView()
            ));
        }

        $result = $provider->findLocations($form);
        $map = $this->buildMap($template, $result);

        return $this->render($template, array(
            'form' => $form->createView(),
            'result' => $result,
            'map' => $map
        ));
    }

    /**
     * Builds a map of locations
     *
     * @param string $template
     * @param Search $result
     *
     * @return \Ivory\GoogleMap\Map
     */
    protected function buildMap($template, Search $result)
    {
        $map = $this->getMap();

        if (!$result->hasResults()) { //in case of no result we center on the searched location
            $map->setCenter($result->getCenter()->getLatitude(), $result->getCenter()->getLongitude());

            return $map;
        }

        $twigTemplate = $this->get('twig')->loadTemplate($template);
        $map->setAutoZoom(true); //this is important to set before adding markers

        foreach ($result->getResults() as $result) { /* @var $result Result */

            $location = $result->location;

            if (!$location instanceof GeolocatableInterface) {
                continue;
            }

            $marker = $this->getMarker();
            $marker->setPosition($location->getLatitude(), $location->getLongitude());

            if ($twigTemplate->hasBlock('teneleven_geolocator_item_window')) {

                $infoWindow = $this->getInfoWindow();
                $infoWindow->setContent($twigTemplate->renderBlock(
                    'teneleven_geolocator_item_window',
                    array('result' => $result)
                ));

                $marker->setInfoWindow($infoWindow);
                $result->mapWindowId = $infoWindow->getJavascriptVariable();
            }

            $result->mapMarkerId = $marker->getJavascriptVariable();
            $map->addMarker($marker);
        }

        return $map;
    }

    /**
     * Get the specified location provider
     *
     * @param string $entity
     * @return LocationProviderInterface
     */
    public function getLocationProvider($entity)
    {
        $providers = $this->get('teneleven.geolocator.providers');

        return $providers->getProvider($entity);
    }

    /**
     * @return \Ivory\GoogleMap\Map
     */
    public function getMap()
    {
        return $this->get('ivory_google_map.map');
    }

    /**
     * @return \Ivory\GoogleMap\Overlays\Marker
     */
    public function getMarker()
    {
        return $this->get('ivory_google_map.marker');
    }

    /**
     * @return \Ivory\GoogleMap\Overlays\InfoWindow
     */
    public function getInfoWindow()
    {
        return $this->get('ivory_google_map.info_window');
    }
}
