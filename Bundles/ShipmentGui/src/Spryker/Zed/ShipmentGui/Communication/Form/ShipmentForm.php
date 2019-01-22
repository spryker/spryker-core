<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ShipmentGui\Business\ShipmentGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentForm extends AbstractType
{
    public const FIELD_ID_SALES_SHIPMENT = 'idSalesShipment';
    public const FIELD_SHIPMENT_ADDRESS_ID = 'idShippingAddress';
    public const FIELD_ADDRESS = 'shippingAddress';
    public const FIELD_ORDER_ITEMS = 'orderItems';
    public const FIELD_SHIPMENT_METHOD = 'method';
    public const FIELD_SHIPMENT_DATE = 'requestedDeliveryDate';

    public const OPTION_SHIPMENT_METHOD = 'choicesShipmentMethod';
    public const OPTION_SHIPMENT_ADDRESS = 'choicesShipmentAddress';
    public const OPTION_SELECTED_ORDER_ITEMS = 'choicesOrderItems';

    public const VALIDITY_DATETIME_FORMAT = 'yyyy-MM-dd H:mm:ss';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(AddressForm::OPTION_SALUTATION_CHOICES);

        $resolver->setRequired(self::FIELD_ORDER_ITEMS);
        $resolver->setRequired(self::OPTION_SHIPMENT_METHOD);
        $resolver->setRequired(self::OPTION_SHIPMENT_ADDRESS);
        $resolver->setDefault(self::OPTION_SELECTED_ORDER_ITEMS, []);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdSalesShipmentField($builder)
            ->addShipmentAddressIdField($builder)
            ->addAddressForm($builder)
            ->addOrderItemsForm($builder)
            ->addShipmentMethodField($builder)
            ->addDeliveryDateField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdSalesShipmentField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_SALES_SHIPMENT, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addShipmentAddressIdField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_SHIPMENT_ADDRESS_ID,
            ChoiceType::class,
            [
                'choices' => array_flip($builder->getOption(self::OPTION_SHIPMENT_ADDRESS)),
                'label' => 'Delivery Address',
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddressForm(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_ADDRESS,
            AddressForm::class,
            [
                AddressForm::OPTION_SALUTATION_CHOICES => $builder->getOption(AddressForm::OPTION_SALUTATION_CHOICES),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addOrderItemsForm(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_ORDER_ITEMS,
            CollectionType::class,
            [
                'entry_type' => OrderItemForm::class,
                'entry_options' => [
                    'label' => false,
                    OrderItemForm::ASSIGNED_ID_COLLECTION => $builder->getOption(self::OPTION_SELECTED_ORDER_ITEMS),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addShipmentMethodField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_SHIPMENT_METHOD,
            ChoiceType::class,
            [
                'choices' => array_flip($builder->getOption(self::OPTION_SHIPMENT_METHOD)),
                'label' => 'Shipment Method',
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addDeliveryDateField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_SHIPMENT_DATE,
            TextType::class,
            [
                'label' => 'Requsted Delivery Date',
                'required' => false,
            ]
        );

        return $this;
    }
}
