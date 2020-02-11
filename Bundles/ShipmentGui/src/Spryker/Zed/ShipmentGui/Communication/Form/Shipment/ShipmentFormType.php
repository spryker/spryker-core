<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Shipment;

use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Address\AddressFormType;
use Spryker\Zed\ShipmentGui\Communication\Form\Validator\Constraints\GreaterThanOrEqualDate;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentFormType extends AbstractType
{
    public const VALIDATION_GROUP_SHIPPING_ADDRESS = 'shippingAddress';
    public const OPTION_SHIPMENT_ADDRESS_CHOICES = 'address_choices';
    public const FIELD_ID_SALES_SHIPMENT = 'idSalesShipment';
    public const FIELD_REQUESTED_DELIVERY_DATE = 'requestedDeliveryDate';
    public const FIELD_SHIPPING_ADDRESS_FORM = 'shippingAddress';
    public const FIELD_SHIPMENT_METHOD_FORM = 'method';
    public const OPTION_SALUTATION_CHOICES = AddressFormType::OPTION_SALUTATION_CHOICES;
    public const FIELD_ID_SHIPMENT_METHOD = ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD;
    public const OPTION_SHIPMENT_METHOD_CHOICES = ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES;
    public const OPTION_ID_SHIPMENT_METHOD = ShipmentMethodFormType::FIELD_ID_SHIPMENT_METHOD;

    protected const FIELD_REQUESTED_DELIVERY_DATE_FORMAT = 'yyyy-MM-dd';
    protected const VALIDATION_DATE_TODAY = 'today';
    protected const VALIDATION_INVALID_DATE_MESSAGE = 'Date should be in correct format %s.';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_SALUTATION_CHOICES)
            ->setRequired(static::FIELD_ID_SHIPMENT_METHOD)
            ->setRequired(static::OPTION_SHIPMENT_METHOD_CHOICES)
            ->setRequired(static::FIELD_ID_SALES_SHIPMENT)
            ->setRequired(static::OPTION_SHIPMENT_ADDRESS_CHOICES)
            ->setDefaults([
                'data_class' => ShipmentTransfer::class,
            ]);
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
            ->addShipmentMethodForm($builder, $options)
            ->addShippingAddressForm($builder, $options)
            ->addIdSalesShipmentField($builder)
            ->addRequestedDeliveryDateField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentMethodForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_SHIPMENT_METHOD_FORM, ShipmentMethodFormType::class, [
            ShipmentMethodFormType::OPTION_ID_SHIPMENT_METHOD => $options[static::OPTION_ID_SHIPMENT_METHOD],
            ShipmentMethodFormType::OPTION_SHIPMENT_METHOD_CHOICES => $options[static::OPTION_SHIPMENT_METHOD_CHOICES],
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
        $builder->add(static::FIELD_REQUESTED_DELIVERY_DATE, DateType::class, [
            'label' => false,
            'required' => false,
            'widget' => 'single_text',
            'input' => 'string',
            'format' => static::FIELD_REQUESTED_DELIVERY_DATE_FORMAT,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
            'constraints' => [
                $this->createDateConstraint(),
                $this->createDateGreaterThanOrEqualConstraint(static::VALIDATION_DATE_TODAY),
            ],
            'validation_groups' => function (FormInterface $form) {
                $formParent = $form->getParent();
                if ($formParent === null || !$formParent->has(static::VALIDATION_DATE_TODAY)) {
                    return [static::VALIDATION_GROUP_SHIPPING_ADDRESS];
                }

                $dateValue = $formParent->get(static::VALIDATION_DATE_TODAY)->getData();
                if ($dateValue === '') {
                    return false;
                }

                return [static::VALIDATION_GROUP_SHIPPING_ADDRESS];
            },
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShippingAddressForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_SHIPPING_ADDRESS_FORM, AddressFormType::class, [
            'label' => false,
            AddressFormType::OPTION_SALUTATION_CHOICES => $options[static::OPTION_SALUTATION_CHOICES],
            AddressFormType::OPTION_SHIPMENT_ADDRESS_CHOICES => $options[static::OPTION_SHIPMENT_ADDRESS_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdSalesShipmentField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_SALES_SHIPMENT, HiddenType::class);

        $builder->get(static::FIELD_ID_SALES_SHIPMENT)
            ->addModelTransformer($this->getFactory()->createStringToNumberTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Date
     */
    protected function createDateConstraint(): Date
    {
        return new Date([
            'message' => sprintf(
                static::VALIDATION_INVALID_DATE_MESSAGE,
                GreaterThanOrEqualDate::VALIDATION_VALID_DATE_FORMAT
            ),
        ]);
    }

    /**
     * @param string $minDate
     *
     * @return \Spryker\Zed\ShipmentGui\Communication\Form\Validator\Constraints\GreaterThanOrEqualDate
     */
    protected function createDateGreaterThanOrEqualConstraint(string $minDate): GreaterThanOrEqualDate
    {
        return new GreaterThanOrEqualDate($minDate);
    }
}
