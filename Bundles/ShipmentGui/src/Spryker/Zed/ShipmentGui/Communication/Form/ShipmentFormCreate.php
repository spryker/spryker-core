<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use DateTime;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressForm;
use Spryker\Zed\ShipmentGui\Communication\Form\Item\ItemForm;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentFormCreate extends AbstractType
{
    public const FIELD_ID_SALES_SHIPMENT = 'id_sales_shipment';
    public const FIELD_ID_SALES_ORDER = 'id_sales_order';
    public const FIELD_ID_SHIPMENT_ADDRESS = 'id_shipping_address';
    public const FIELD_ID_SHIPMENT_METHOD = 'id_shipment_method';
    public const FIELD_REQUESTED_DELIVERY_DATE = 'requested_delivery_date';
    public const FIELD_SHIPMENT_SELECTED_ITEMS = 'selected_items';

    public const FORM_SHIPPING_ADDRESS = 'shipping_address';
    public const FORM_SALES_ORDER_ITEMS = 'items';

    public const OPTION_SHIPMENT_ADDRESS_CHOICES = 'address_choices';
    public const OPTION_SHIPMENT_METHOD_CHOICES = 'method_choices';

    public const VALIDITY_DATETIME_FORMAT = 'yyyy-MM-dd H:mm:ss';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_SHIPMENT_ADDRESS_CHOICES);
        $resolver->setRequired(static::OPTION_SHIPMENT_METHOD_CHOICES);
        $resolver->setRequired(static::FIELD_SHIPMENT_SELECTED_ITEMS);
        $resolver->setRequired(AddressForm::OPTION_SALUTATION_CHOICES);
        $resolver->setRequired(ItemForm::OPTION_ORDER_ITEMS_CHOICES);
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
            ->addIdSalesShipmentField($builder)
            ->addIdShipmentAddressField($builder, $options[static::OPTION_SHIPMENT_ADDRESS_CHOICES])
            ->addIdShipmentMethodField($builder, $options[static::OPTION_SHIPMENT_METHOD_CHOICES])
            ->addRequestedDeliveryDateField($builder)
            ->addShippingAddressForm($builder, $options)
            ->addOrderItemsForm($builder, $options[ItemForm::OPTION_ORDER_ITEMS_CHOICES]);
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
     * @param array $options
     *
     * @return $this
     */
    protected function addIdShipmentAddressField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ID_SHIPMENT_ADDRESS, ChoiceType::class, [
            'label' => 'Delivery Address',
            'choices' => array_flip($options),
            'required' => false,
            'placeholder' => false,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addIdShipmentMethodField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ID_SHIPMENT_METHOD, ChoiceType::class, [
            'label' => 'Shipment Method',
            'placeholder' => 'Select one',
            'choices' => array_flip($options),
            'required' => true,
            'constraints' => [
                new Required(),
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addRequestedDeliveryDateField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_REQUESTED_DELIVERY_DATE, DateTimeType::class, [
            'label' => 'Delivery Date',
            'format' => static::VALIDITY_DATETIME_FORMAT,
            'required' => false,
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datepicker js-requested-datetime safe-datetime',
            ],
        ]);

        $this->addDateTimeTransformer(static::FIELD_REQUESTED_DELIVERY_DATE, $builder);

        return $this;
    }

    /**
     * @param string $fieldName
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addDateTimeTransformer($fieldName, FormBuilderInterface $builder): void
    {
        $timeFormat = static::VALIDITY_DATETIME_FORMAT;

        $builder
            ->get($fieldName)
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsString) {
                    if (!$dateAsString) {
                        return null;
                    }

                    return new DateTime($dateAsString);
                },
                function ($dateAsObject) use ($timeFormat) {
                    /** @var \DateTime|null $dateAsObject */
                    if (!$dateAsObject) {
                        return null;
                    }

                    return $dateAsObject->format($timeFormat);
                }
            ));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShippingAddressForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FORM_SHIPPING_ADDRESS, AddressForm::class, [
            'label' => false,
            AddressForm::OPTION_SALUTATION_CHOICES => $options[AddressForm::OPTION_SALUTATION_CHOICES],
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
                'entry_type' => ItemForm::class,
                'entry_options' => [
                    'label' => false,
                    static::FIELD_SHIPMENT_SELECTED_ITEMS => $builder->getOption(static::FIELD_SHIPMENT_SELECTED_ITEMS),
                ],
        ]);
        return $this;
    }
}
