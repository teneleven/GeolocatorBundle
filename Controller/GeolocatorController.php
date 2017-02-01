<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Controller;

use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlay\InfoWindow;
use Ivory\GoogleMap\Overlay\Marker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Geocoder\Exception\QuotaExceeded;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teneleven\Bundle\GeolocatorBundle\Model\GeolocatableInterface;
use Teneleven\Bundle\GeolocatorBundle\Model\Result;
use Teneleven\Bundle\GeolocatorBundle\Model\Search;
use Teneleven\Bundle\GeolocatorBundle\Provider\LocationProviderInterface;

/**
 * Geolocator Controller
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class GeolocatorController extends Controller
{
    /**
     * Displays a geo-locator screen with map, form, and locations
     *
     * @param string  $entity   The entity key that the provider is registered under
     * @param Request $request
     * @param string  $template The template to render
     *
     * @return Response
     */
    public function locate($entity, Request $request, $template = null)
    {
        if (!$template) {
            $template = 'TenelevenGeolocatorBundle::results.html.twig';
        }

        $provider = $this->getLocationProvider($entity);
        $form = $this->get('form.factory')->createNamed('', $provider->getFilterFormType(), null, array('method' => 'GET', 'csrf_protection' => false));

        try {
            $form->handleRequest($request);
        } catch (QuotaExceeded $e) {
            $this->get('logger')->error($e->getMessage());
            $this->get('session')->getFlashBag()->add('error', 'Sorry, this locator has exceeded the quota for location look-ups. Please try again at a later time.');
        }

        if (!$form->isValid()) {
            return $this->render($template, array(
                'map' => $map = $this->getMap(),
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

    private function getMap()
    {
        $map = new Map();
        $map->setStylesheetOption('height', '500px');
        $map->setStylesheetOption('width', '100%');

        return $map;
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
            $map->setCenter(
                new Coordinate(
                    $result->getCenter()->getLatitude(),
                    $result->getCenter()->getLongitude()
                )
            );

            return $map;
        }

        $twigTemplate = $this->get('twig')->loadTemplate($template);
        $map->setAutoZoom(true); //this is important to set before adding markers

        foreach ($result->getResults() as $result) { /* @var $result Result */

            $location = $result->location;

            if (!$location instanceof GeolocatableInterface) {
                continue;
            }

            $marker = new Marker(
                new Coordinate(
                    $location->getLatitude(),
                    $location->getLongitude()
                )
            );

            if ($twigTemplate->hasBlock('teneleven_geolocator_item_window', [])) {

                $infoWindow = new InfoWindow($twigTemplate->renderBlock(
                    'teneleven_geolocator_item_window',
                    array('result' => $result)
                ));

                $marker->setInfoWindow($infoWindow);
                $result->mapWindowId = $infoWindow->getVariable();
            }

            $result->mapMarkerId = $marker->getVariable();
            $map->getOverlayManager()->addMarker($marker);
        }

        return $map;
    }

    /**
     * Get the specified location provider
     *
     * @param  string                    $entity
     * @return LocationProviderInterface
     */
    public function getLocationProvider($entity)
    {
        $providers = $this->get('teneleven.geolocator.providers');

        return $providers->getProvider($entity);
    }
}
