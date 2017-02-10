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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Geocoder\Model\Coordinates;
use Symfony\Component\Form\Form;
use Teneleven\Bundle\GeolocatorBundle\Form\Type\AddressLocatorType;
use Teneleven\Bundle\GeolocatorBundle\Model\Result;
use Teneleven\Bundle\GeolocatorBundle\Model\Search;
use Teneleven\Bundle\GeolocatorBundle\Util\UnitConverter;

/**
 * Default location provider
 *
 * @author Daniel Richter <danny@1011i.com>
 */
class LocationProvider implements LocationProviderInterface
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var integer
     */
    protected $radius;

    /**
     * @var integer
     */
    protected $limit;

    /**
     * Constructor
     *
     * @param EntityRepository $repository
     * @param integer          $radius     The search radius in miles
     * @param integer          $limit
     */
    public function __construct(EntityRepository $repository, $radius = null, $limit = null)
    {
        $this->repository = $repository;
        $this->radius = $radius;
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function findLocations(Form $form)
    {
        $searchCenter = $form->get('location')->getData();

        $results = $this->getQueryBuilder($searchCenter)
            ->orderBy('distance')
            ->getQuery()
            ->execute();

        return $this->decorateResults($searchCenter, $results);
    }

    /**
     * @param  Coordinates $searchCenter
     * @return QueryBuilder
     */
    protected function getQueryBuilder(Coordinates $searchCenter)
    {
        $queryBuilder = $this->repository->createQueryBuilder('l');

        $queryBuilder->select('l, GEO_DISTANCE(:latitude, :longitude, l.latitude, l.longitude) AS distance')
            ->where($queryBuilder->expr()->isNotNull('l.latitude'))
            ->andWhere($queryBuilder->expr()->isNotNull('l.longitude'))
            ->setParameter('latitude', $searchCenter->getLatitude())
            ->setParameter('longitude', $searchCenter->getLongitude())
        ;

        if ($this->radius) {
            $queryBuilder
                ->having('distance <= :radius')
                ->setParameter('radius', UnitConverter::milesToKm($this->radius))
            ;
        }

        if ($this->limit) {
            $queryBuilder->setMaxResults($this->limit);
        }

        return $queryBuilder;
    }

    /**
     * Helper method to decorate search results
     *
     * @param  Coordinates $searchCenter
     * @param  array           $results
     * @return Search
     */
    protected function decorateResults(Coordinates $searchCenter, array $results)
    {
        $search = new Search();
        $search->setCenter($searchCenter);

        foreach ($results as $result) {
            $distanceInMiles = UnitConverter::kmToMiles($result['distance']);
            $search->addResult(new Result($result[0], $distanceInMiles));
        }

        return $search;
    }

    /**
     * {@inheritdoc}
     */
    public function getUncodedLocations()
    {
        return $this->repository->findBy(array('latitude' => null));
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterFormType()
    {
        return AddressLocatorType::class;
    }
}
