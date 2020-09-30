<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
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
    protected const FIELD_SHIPMENT_FORM = 'shipment';
    protected const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';
    protected const FIELD_SALES_ORDER_ITEMS_FORM = 'items';
    protected const FIELD_ID_SALES_SHIPMENT = 'idSalesShipment';
    protected const FIELD_SHIPMENT_SELECTED_ITEMS = 'selected_items';

    protected const OPTION_SHIPMENT_METHOD_CHOICES = 'method_choices';
    protected const OPTION_SALUTATION_CHOICES = 'salutation_choices';
    protected const OPTION_SHIPMENT_ADDRESS_CHOICES = 'address_choices';
    protected const OPTION_ORDER_ITEMS_CHOICES = 'items_choices';

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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addOrderItemsFormType($builder, $options)
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
        /** @var \Spryker\Zed\ShipmentGui\Communication\Plugin\Form\ShipmentFormTypePlugin $shipmentFormTypePlugin */
        $shipmentFormTypePlugin = $this->getFactory()->getShipmentFormTypePlugin();
        $builder->add(static::FIELD_SHIPMENT_FORM, $shipmentFormTypePlugin->getType(), [
            static::FIELD_ID_SALES_SHIPMENT => $options[static::FIELD_ID_SALES_SHIPMENT],
            static::OPTION_SHIPMENT_ADDRESS_CHOICES => $options[static::OPTION_SHIPMENT_ADDRESS_CHOICES],
            static::OPTION_SHIPMENT_METHOD_CHOICES => $options[static::OPTION_SHIPMENT_METHOD_CHOICES],
            static::FIELD_ID_SHIPMENT_METHOD => $options[static::FIELD_ID_SHIPMENT_METHOD],
            static::OPTION_SALUTATION_CHOICES => $options[static::OPTION_SALUTATION_CHOICES],
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
    protected function addOrderItemsFormType(FormBuilderInterface $builder, array $options = [])
    {
        /** @var \Spryker\Zed\ShipmentGui\Communication\Plugin\Form\ItemFormTypePlugin $itemFormTypePlugin */
        $itemFormTypePlugin = $this->getFactory()->getItemFormTypePlugin()->getType();
        $builder->add(static::FIELD_SALES_ORDER_ITEMS_FORM, CollectionType::class, [
            'entry_type' => $itemFormTypePlugin->getType(),
            'entry_options' => [
                'label' => false,
                static::FIELD_SHIPMENT_SELECTED_ITEMS => $options[static::FIELD_SHIPMENT_SELECTED_ITEMS],
                static::OPTION_ORDER_ITEMS_CHOICES => $options[static::OPTION_ORDER_ITEMS_CHOICES],
            ],
        ]);

        return $this;
    }
}
