<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShipmentForm extends AbstractType
{
    public const FIELD_SHIPMENT_ADDRESS_ID = 'idShippingAddress';
    public const FIELD_ADDRESS = 'shippingAddress';
    public const FIELD_ORDER_ITEMS = 'order_items';
    public const FIELD_DELIVERY_ADDRESS = 'delivery_address';
    public const CHOICES_SHIPMENT_METHOD = 'choices_shipment_method';
    public const CHOICES_SHIPMENT_ADDRESS = 'choices_shipment_address';
    public const FIELD_SHIPMENT_DATE = 'requestedDeliveryDate';
    public const FIELD_SHIPMENT_METHOD = 'method';
    public const SELECTED_ORDER_ITEMS = 'selected_order_items';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(AddressForm::OPTION_SALUTATION_CHOICES);
        $resolver->setRequired(AddressForm::OPTION_COUNTRY_CHOICES);
        $resolver->setRequired(self::CHOICES_SHIPMENT_METHOD);
        $resolver->setRequired(self::CHOICES_SHIPMENT_ADDRESS);
        $resolver->setDefault(self::SELECTED_ORDER_ITEMS, []);
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
            ->addShipmentAddressIdField($builder)
            ->addAddressForm($builder)
            ->addOrderItemsForm($builder)
            ->addShipmentMethodField($builder)
            ->addDeliveryDateField($builder)
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addShipmentAddressIdField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_SHIPMENT_ADDRESS_ID,
            ChoiceType::class,
            [
                'choices' => array_flip($builder->getOption(self::CHOICES_SHIPMENT_ADDRESS)),
                'label' => 'Delivery Address',
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
                'entry_type' => OrderItemType::class,
                'entry_options' => [
                    'label' => false,
                    OrderItemType::ASSIGNED_ID_COLLECTION => $builder->getOption(self::SELECTED_ORDER_ITEMS),
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
    public function addDeliveryAddressField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_ORDER_ITEMS,
            CollectionType::class,
            []
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
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
                AddressForm::OPTION_COUNTRY_CHOICES=> $builder->getOption(AddressForm::OPTION_COUNTRY_CHOICES),
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
            ShipmentMethodType::class,
            [
                'choices' => $builder->getOption(self::CHOICES_SHIPMENT_METHOD),
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
                'required' => false,
            ]
        );

        return $this;
    }
}
