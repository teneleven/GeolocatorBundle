<?php

namespace Teneleven\Bundle\GeolocatorBundle\Provider;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Geocoder\Result\ResultInterface;
use Symfony\Component\Form\Form;
use Teneleven\Bundle\GeolocatorBundle\Model\Result;
use Teneleven\Bundle\GeolocatorBundle\Model\Search;
use Teneleven\Bundle\GeolocatorBundle\Util\UnitConverter;

/**
 * Default location provider
 */
class LocationProvider implements LocationProviderInterface
{
    /**
     * @var ObjectRepository
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
     * @param ObjectRepository $repository
     * @param integer          $radius     The search radius in miles
     * @param integer          $limit
     */
    public function __construct(ObjectRepository $repository, $radius = null, $limit = null)
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
        $searchCenter = $form->getData();

        $results = $this->getQueryBuilder($searchCenter)
            ->orderBy('distance')
            ->getQuery()
            ->execute();

        return $this->decorateResults($searchCenter, $results);
    }

    /**
     * @param  ResultInterface $searchCenter
     * @return QueryBuilder
     */
    protected function getQueryBuilder(ResultInterface $searchCenter)
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
     * @param  ResultInterface $searchCenter
     * @param  array           $results
     * @return Search
     */
    protected function decorateResults(ResultInterface $searchCenter, array $results)
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
        return 'teneleven_geolocator_address_locator';
    }
}
