<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Shipment;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentGroupFormType extends AbstractType
{
    public const FIELD_ID_SALES_SHIPMENT = ShipmentFormType::FIELD_ID_SALES_SHIPMENT;
    public const FIELD_ID_SALES_ORDER = 'id_sales_order';
    public const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';
    public const FIELD_SHIPMENT_SELECTED_ITEMS = ItemFormType::FIELD_SHIPMENT_SELECTED_ITEMS;
    public const OPTION_SHIPMENT_ADDRESS_CHOICES = ShipmentFormType::OPTION_SHIPMENT_ADDRESS_CHOICES;
    public const FIELD_ID_SHIPMENT_METHOD = ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD;
    public const OPTION_ORDER_ITEMS_CHOICES = ItemFormType::OPTION_ORDER_ITEMS_CHOICES;

    public const FORM_SHIPMENT = 'shipment';
    public const FORM_SALES_ORDER_ITEMS = 'items';

    public const OPTION_SHIPMENT_METHOD_CHOICES = ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES;
    public const OPTION_SALUTATION_CHOICES = AddressFormType::OPTION_SALUTATION_CHOICES;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_SHIPMENT_ADDRESS_CHOICES)
            ->setRequired(static::FIELD_ID_SHIPMENT_METHOD)
            ->setRequired(static::OPTION_ORDER_ITEMS_CHOICES)
            ->setRequired(static::OPTION_SHIPMENT_METHOD_CHOICES)
            ->setRequired(static::OPTION_SALUTATION_CHOICES)
            ->setRequired(static::FIELD_SHIPMENT_SELECTED_ITEMS)
            ->setRequired(static::FIELD_ID_SALES_SHIPMENT);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addOrderItemsForm($builder, $options)
            ->addShipmentFormType($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentFormType(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FORM_SHIPMENT, ShipmentFormType::class, [
            ShipmentFormType::FIELD_ID_SALES_SHIPMENT => $options[static::FIELD_ID_SALES_SHIPMENT],
            ShipmentFormType::OPTION_SHIPMENT_ADDRESS_CHOICES => $options[static::OPTION_SHIPMENT_ADDRESS_CHOICES],
            ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES => $options[static::OPTION_SHIPMENT_METHOD_CHOICES],
            ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD => $options[static::FIELD_ID_SHIPMENT_METHOD],
            AddressFormType::OPTION_SALUTATION_CHOICES => $options[static::OPTION_SALUTATION_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOrderItemsForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FORM_SALES_ORDER_ITEMS, CollectionType::class, [
            'entry_type' => ItemFormType::class,
            'entry_options' => [
                'label' => false,
                static::FIELD_SHIPMENT_SELECTED_ITEMS => $options[static::FIELD_SHIPMENT_SELECTED_ITEMS],
                static::OPTION_ORDER_ITEMS_CHOICES => $options[static::OPTION_ORDER_ITEMS_CHOICES],
            ],
        ]);

        return $this;
    }
}
