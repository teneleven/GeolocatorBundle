<?php

namespace Teneleven\Bundle\GeolocatorBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * AddressLookupType
 */
class AddressLookupType extends AbstractType
{
    protected $transformer;

    public function __construct(DataTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);

        parent::buildForm($builder, $options);
    }

    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'teneleven_geolocator_address_lookup';
    }
}