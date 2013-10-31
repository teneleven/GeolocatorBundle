<?php

namespace Teneleven\Bundle\GeolocatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Teneleven\Bundle\GeolocatorBundle\Model\GeoLocatableInterface;


/**
 * Command to geocode entities
 */
class GeocodeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('teneleven:geolocator:geocode')
            ->setDescription('Geocodes an entity type')
            ->addArgument('entity', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The entity type(s) to encode')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $providers \Teneleven\Bundle\GeolocatorBundle\Provider\LocationProviderRegistry */
        $providers = $this->getContainer()->get('teneleven.geolocator.providers');
        $entities = $input->getArgument('entity');

        if (!count($entities)) {
            $entities = array_keys($providers->getProviders());
        }

        /* @var $geocoder \Teneleven\Bundle\GeolocatorBundle\ObjectGeocoder */
        $geocoder = $this->getContainer()->get('teneleven.geolocator.geocoder');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($entities as $entity) {

            $output->write(sprintf('Processing entity type "%s"...', $entity));

            $provider = $providers->getProvider($entity);
            $locations = $provider->getUncodedLocations();

            $output->writeln(sprintf('%s uncoded objects.', count($locations)));

            if (!count($locations)) {
                continue;
            }

            foreach ($locations as $location) {

                try {
                    $address = $provider->extractAddress($location);
                    $result = $geocoder->geocode($address);
                    $provider->updateGeocoordinates($location, $result);
                } catch (\Exception $e) {
                    $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
                    continue;
                }

                $output->writeln(sprintf('<info>Success (Lat: %s, Lng: %s)</info>', $location->getLatitude(), $location->getLongitude()));

                //consider flushing in between
            }

            $em->flush();
        }

    }
}
