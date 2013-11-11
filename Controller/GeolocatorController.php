<?php

namespace Teneleven\Bundle\GeolocatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Teneleven\Bundle\GeolocatorBundle\Model\GeoLocatableInterface;
use Teneleven\Bundle\GeolocatorBundle\Provider\LocationProviderInterface;

/**
 * Geolocator Controller
 */
class GeolocatorController extends Controller
{
    /**
     * Displays a geo-locator screen with map, form, and locations
     *
     * @param string $entity the entity key that the provider is registered under
     * @param Request $request
     * @param string $template
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function locate($entity, Request $request, $template = 'TenelevenGeolocatorBundle::results.html.twig')
    {
        $provider = $this->getLocationProvider($entity);
        $form = $this->get('form.factory')->createNamed('query', $provider->getFilterFormType(), null, array('method' => 'GET'));

        $results = array();

        if ($form->handleRequest($request)->isValid()) {
            $results = $provider->findLocations($form->getData());
        }

        return $this->renderLocations($template, $results, $form);
    }

    /**
     * Helper method to render the response
     *
     * @param string $template
     * @param array $results
     * @param Form $form
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLocations($template, $results, Form $form)
    {
        return $this->render($template, array(
            'form' => $form->createView(),
            'results' => $results,
            'searchLocation' => $form->isSubmitted() && $form->isValid() ? $form->getData() : null,
            'map' => $this->buildMap($template, $results, $jsIds),
            'jsIds' => $jsIds
        ));
    }

    /**
     * Builds a map of locations
     *
     * @param string $template
     * @param array $results
     * @param array $jsIds
     *
     * @return \Ivory\GoogleMap\Map
     */
    protected function buildMap($template, $results, &$jsIds)
    {
        $jsIds = array('markers' => array(), 'windows' => array());
        $twigTemplate = $this->get('twig')->loadTemplate($template);
        $map = $this->getMap();

        foreach ($results as $id => $result) {

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
                $jsIds['windows'][$id] = $infoWindow->getJavascriptVariable();
            }

            $jsIds['markers'][$id] = $marker->getJavascriptVariable();

            $map->addMarker($marker);
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
