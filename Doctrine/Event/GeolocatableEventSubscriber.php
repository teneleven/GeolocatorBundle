<?php

namespace TenEleven\Bundle\GeolocatableBundle\Doctrine\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Geocoder\Provider\ProviderInterface;
use TenEleven\Bundle\GeolocatableBundle\Model\GeolocatableInterface;

/**
 * Subscribes to Doctrine prePersist and preUpdate to update an Entity's latitude and longitude
 *
 * @author justinhilles
 */
class GeolocatableEventSubscriber implements EventSubscriber {
    protected $geocoder;
    
    public function __construct(ProviderInterface $geocoder){
        $this->geocoder = $geocoder;
    }

    /**
    * Specifies the list of events to listen
    *
    * @return array
    */
    public function getSubscribedEvents(){
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
    public function prePersist(LifecycleEventArgs $eventArgs){
        if(($entity = $eventArgs->getEntity()) instanceof GeolocatableInterface){
            if( !$entity->getLatitude() || !$entity->getLongitude()){
                $this->geocodeEntity($entity, $this->geocoder);
            }
        }
    }
    
    /**
     * Sets an updating Entity's latitude and longitude if not present 
     * or any part of address updated
     * 
     * @param PreUpdateEventArgs $eventArgs 
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs){
        if(($entity = $eventArgs->getEntity()) instanceof GeolocatableInterface){
            if( !$dealer->getLatitude() || !$dealer->getLongitude() 
                || $eventArgs->hasChangedField('street') || $eventArgs->hasChangedField('city') 
                || $eventArgs->hasChangedField('state') || $eventArgs->hasChangedField('zip')){
                $this->geocodeAddress($dealer, $this->geocoder);
                
                $em = $eventArgs->getEntityManager();
                $uow = $em->getUnitOfWork();
                $meta = $em->getClassMetadata(get_class($dealer));
                $uow->recomputeSingleEntityChangeSet($meta, $dealer);
            }
        }
    }
    
    /**
     * Geocode and set the Entity's latitude and longitude
     * 
     * @param type $apartment 
     */
    private function geocodeEntity(GeolocatableInterface $entity, ProviderInterface $geocoder){
        $result = $geocoder->getGeocodedData($entity->getGeolocatableAddress());

        if(isset($result[0])){
            $entity->setLatitude($result[0]['latitude']);
            $entity->setLongitude($result[0]['longitude']);            
        }
    }
    
}
