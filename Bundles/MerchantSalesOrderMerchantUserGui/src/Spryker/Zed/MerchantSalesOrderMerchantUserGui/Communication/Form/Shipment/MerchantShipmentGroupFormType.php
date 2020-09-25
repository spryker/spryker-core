<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentMethodFormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\MerchantSalesOrderMerchantUserGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\MerchantSalesOrderMerchantUserGuiCommunicationFactory getFactory()
 */
class MerchantShipmentGroupFormType extends AbstractType
{
    public const FIELD_ID_SALES_SHIPMENT = ShipmentFormType::FIELD_ID_SALES_SHIPMENT;
    public const FIELD_ID_SALES_ORDER = 'id_sales_order';
    public const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';
    public const FIELD_SHIPMENT_SELECTED_ITEMS = ItemFormType::FIELD_SHIPMENT_SELECTED_ITEMS;
    public const OPTION_SHIPMENT_ADDRESS_CHOICES = ShipmentFormType::OPTION_SHIPMENT_ADDRESS_CHOICES;
    public const FIELD_ID_SHIPMENT_METHOD = ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD;
    public const OPTION_ORDER_ITEMS_CHOICES = ItemFormType::OPTION_ORDER_ITEMS_CHOICES;

    public const FIELD_SHIPMENT_FORM = 'shipment';
    public const FIELD_SALES_ORDER_ITEMS_FORM = 'items';

    public const OPTION_SHIPMENT_METHOD_CHOICES = ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES;
    public const OPTION_SALUTATION_CHOICES = AddressFormType::OPTION_SALUTATION_CHOICES;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_SHIPMENT_ADDRESS_CHOICES)
            ->setRequired(static::FIELD_ID_SHIPMENT_METHOD)
            ->setRequired(static::OPTION_ORDER_ITEMS_CHOICES)
            ->setRequired(static::OPTION_SHIPMENT_METHOD_CHOICES)
            ->setRequired(static::OPTION_SALUTATION_CHOICES)
            ->setRequired(static::FIELD_SHIPMENT_SELECTED_ITEMS)
            ->setRequired(static::FIELD_ID_SALES_SHIPMENT)
            ->setDefaults([
                'data_class' => ShipmentGroupTransfer::class,
            ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addOrderItemsForm($builder, $options)
            ->addShipmentFormType($builder, $options);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentFormType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SHIPMENT_FORM, ShipmentFormType::class, [
            ShipmentFormType::FIELD_ID_SALES_SHIPMENT => $options[static::FIELD_ID_SALES_SHIPMENT],
            ShipmentFormType::OPTION_SHIPMENT_ADDRESS_CHOICES => $options[static::OPTION_SHIPMENT_ADDRESS_CHOICES],
            ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES => $options[static::OPTION_SHIPMENT_METHOD_CHOICES],
            ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD => $options[static::FIELD_ID_SHIPMENT_METHOD],
            AddressFormType::OPTION_SALUTATION_CHOICES => $options[static::OPTION_SALUTATION_CHOICES],
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOrderItemsForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_SALES_ORDER_ITEMS_FORM, CollectionType::class, [
            'entry_type' => ItemFormType::class,
            'entry_options' => [
                'label' => false,
                ItemFormType::FIELD_SHIPMENT_SELECTED_ITEMS => $options[static::FIELD_SHIPMENT_SELECTED_ITEMS],
                ItemFormType::OPTION_ORDER_ITEMS_CHOICES => $options[static::OPTION_ORDER_ITEMS_CHOICES],
            ],
        ]);

        return $this;
    }
}
