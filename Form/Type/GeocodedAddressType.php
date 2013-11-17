<?php

namespace Teneleven\Bundle\GeolocatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form type which turns a string into a geo-coded address
 */
class GeocodedAddressType extends AbstractType
{
    /**
     * @var DataTransformerInterface
     */
    protected $transformer;

    /**
     * Constructor
     *
     * @param DataTransformerInterface $transformer
     */
    public function __construct(DataTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);

        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'teneleven_geolocator_geocoded_address';
    }
}
