<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Form\DataProvider\ShipmentTypeProductOfferDataProviderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ShipmentTypeProductOfferFormExpander implements ShipmentTypeProductOfferFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPES = ProductOfferTransfer::SHIPMENT_TYPES;

    /**
     * @var string
     */
    protected const LABEL_SHIPMENT_TYPES = 'Shipment Types';

    /**
     * @var string
     */
    protected const PLACEHOLDER_SHIPMENT_TYPES = 'Select';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Form\DataProvider\ShipmentTypeProductOfferDataProviderInterface
     */
    protected ShipmentTypeProductOfferDataProviderInterface $shipmentTypeProductOfferDataProvider;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>|null, list<string>|null>
     */
    protected DataTransformerInterface $shipmentTypeDataTransformer;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Form\DataProvider\ShipmentTypeProductOfferDataProviderInterface $shipmentTypeProductOfferDataProvider
     * @param \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>|null, list<string>|null> $shipmentTypeDataTransformer
     */
    public function __construct(
        ShipmentTypeProductOfferDataProviderInterface $shipmentTypeProductOfferDataProvider,
        DataTransformerInterface $shipmentTypeDataTransformer
    ) {
        $this->shipmentTypeProductOfferDataProvider = $shipmentTypeProductOfferDataProvider;
        $this->shipmentTypeDataTransformer = $shipmentTypeDataTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(static::FIELD_SHIPMENT_TYPES, ChoiceType::class, [
            'label' => static::LABEL_SHIPMENT_TYPES,
            'attr' => [
                'placeholder' => static::PLACEHOLDER_SHIPMENT_TYPES,
            ],
            'choices' => $this->shipmentTypeProductOfferDataProvider->getShipmentTypeChoices(),
            'multiple' => true,
        ]);

        $builder->get(static::FIELD_SHIPMENT_TYPES)->addModelTransformer($this->shipmentTypeDataTransformer);

        return $builder;
    }
}
