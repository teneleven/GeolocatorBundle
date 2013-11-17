<?php

namespace Teneleven\Bundle\GeolocatorBundle\Doctrine\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Geocoder\GeocoderInterface;
use Teneleven\Bundle\GeolocatorBundle\Model\GeolocatableInterface;

/**
 * Subscribes to Doctrine prePersist and preUpdate to update an Entity's latitude and longitude
 *
 * @author justinhilles
 */
class GeolocatableEventSubscriber implements EventSubscriber
{
    /**
     * @var GeocoderInterface
     */
    protected $geocoder;

    /**
     * Constructor
     *
     * @param GeocoderInterface $geocoder
     */
    public function __construct(GeocoderInterface $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
    * Specifies the list of events to listen
    *
    * @return array
    */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }

    /**
     * Sets a new Entity's latitude and longitude if not present
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof GeolocatableInterface) {
            return;
        }

        if ($entity->getLatitude() && $entity->getLongitude()) {
            return;
        }

        $this->geocodeEntity($entity);
    }

    /**
     * Sets and updates Entity's latitude and longitude if not present
     * or any part of address was updated
     *
     * @param PreUpdateEventArgs $eventArgs
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof GeolocatableInterface) {
            return;
        }

        //detect update need
        $needsUpdating = false;

        if ($needsUpdating) {
            $this->geocodeEntity($entity);

            $em = $eventArgs->getEntityManager();
            $uow = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    /**
     * Geocode and set the Entity's latitude and longitude
     */
    private function geocodeEntity(GeolocatableInterface $entity)
    {
        throw new \Exception('Not implemented yet.');

        $address = ''; //get this somehow

        //exception if not

        $result = $this->geocoder->geocode($address);

        //exception if not

        $entity->setLatitude($result->getLatitude());
        $entity->setLongitude($result->getLongitude());
    }
}
