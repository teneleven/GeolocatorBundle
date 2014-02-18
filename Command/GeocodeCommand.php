<?php

/*
* This file is part of the Teneleven Geolocator Bundle.
*
* (c) Teneleven Interactive
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Teneleven\Bundle\GeolocatorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Teneleven\Bundle\GeolocatorBundle\Exception\AddressExtractionFailedException;
use Geocoder\Exception\ExceptionInterface as GeocoderException;
use Teneleven\Bundle\GeolocatorBundle\Model\GeolocatableInterface;

/**
 * Command to geocode entities
 *
 * @author Daniel Richter <danny@1011i.com>
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
            ->addOption('flush-interval', null, InputOption::VALUE_REQUIRED, 'Flush every time this many locations have been coded.', 25)
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $geocoder = $this->getContainer()->get('teneleven.geolocator.geocoder');
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $providers = $this->getContainer()->get('teneleven.geolocator.providers');
        $addressExtractors = $this->getContainer()->get('teneleven.geolocator.address_extractors');
        $entities = $input->getArgument('entity');
        $flushInterval = $input->getOption('flush-interval');

        if (!count($entities)) {
            $entities = array_keys($providers->getProviders());
        }

        $counter = 0;
        foreach ($entities as $entity) {

            $output->write(sprintf('Processing entity type <comment>"%s"</comment>...', $entity));

            $provider = $providers->getProvider($entity);
            $addressExtractor = $addressExtractors->getExtractor($entity);
            $locations = $provider->getUncodedLocations();

            $output->writeln(sprintf('<comment>%s</comment> uncoded objects.', count($locations)));

            if (!count($locations)) {
                continue;
            }

            foreach ($locations as $location) {

                if (!$location instanceof GeolocatableInterface) {
                    throw new \Exception('Geolocatable objects must implement GeolocatableInterface');
                }

                try {
                    $address = $addressExtractor->extractAddress($location);
                    $result = $geocoder->geocode($address);
                } catch (AddressExtractionFailedException $e) { //this error indicates a misconfiguration
                    throw $e;
                } catch (GeocoderException $e) {
                    $output->writeln(sprintf('<error>Error: %s</error>', $e->getMessage()));
                    continue;
                }

                $location->setLatitude($result->getLatitude());
                $location->setLongitude($result->getLongitude());

                $output->writeln(sprintf('<info>Success (Lat: %s, Lng: %s)</info>', $location->getLatitude(), $location->getLongitude()));
                $counter++;

                if ($flushInterval && (0 === $counter % $flushInterval)) {
                    $manager->flush();
                }
            }

            $manager->flush();
        }

        $output->writeln('Done.');
    }
}
