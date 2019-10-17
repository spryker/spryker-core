<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod;

use Spryker\Zed\ShipmentGui\Communication\Provider\ShipmentMethodFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ShipmentMethodForm extends ViewShipmentMethodForm
{
    public const FIELD_NAME_FIELD = 'name';
    public const FIELD_KEY = 'shipmentMethodKey';
    public const FIELD_ID_FIELD = 'idShipmentMethod';
    public const FIELD_IS_ACTIVE = 'isActive';
    public const FIELD_AVAILABILITY_PLUGIN_FIELD = 'availabilityPlugin';
    public const FIELD_PRICE_PLUGIN_FIELD = 'pricePlugin';
    public const FIELD_DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    public const FIELD_CARRIER_FIELD = 'fkShipmentCarrier';
    
    protected const MESSAGE_SHIPMENT_METHOD_NAME_ALREADY_EXISTS_FOR_SELECTED_PROVIDER = 'Shipment method with such name already exists for selected shipment provider.';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addCarrierField($builder, $options)
            ->addKeyField($builder)
            ->addNameField($builder)
            ->addAvailabilityPluginField($builder, $options)
            ->addPricePluginField($builder, $options)
            ->addDeliveryTimePluginField($builder, $options)
            ->addIsActiveField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            ShipmentMethodFormDataProvider::OPTION_CARRIER_CHOICES,
            ShipmentMethodFormDataProvider::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST,
            ShipmentMethodFormDataProvider::OPTION_PRICE_PLUGIN_CHOICE_LIST,
            ShipmentMethodFormDataProvider::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCarrierField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_CARRIER_FIELD, ChoiceType::class, [
            'label' => 'Carrier',
            'placeholder' => 'Select one',
            'choices' => array_flip($options[ShipmentMethodFormDataProvider::OPTION_CARRIER_CHOICES]),
            'constraints' => [
                new NotBlank(),
                new Required(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_KEY, TextType::class, [
            'label' => 'Delivery Method Key',
            'constraints' => [
                new NotBlank(),
                new Required(),
//                new Callback([
//                    'callback' => $this->validateUniqueName($options),
//                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME_FIELD, TextType::class, [
            'label' => 'Name',
            'constraints' => [
                new NotBlank(),
                new Required(),
//                new Callback([
//                    'callback' => $this->validateUniqueName($options),
//                ]),
            ],
        ]);

        return $this;
    }

//    /**
//     * @param array $options
//     *
//     * @return callable
//     */
//    protected function validateUniqueName(array $options): callable
//    {
//        return function ($name, ExecutionContextInterface $contextInterface) use ($options) {
//            if (!$this->getFacade()->isShipmentMethodUniqueForCarrier($options[static::OPTION_DATA])) {
//                $contextInterface->addViolation(static::MESSAGE_SHIPMENT_METHOD_NAME_ALREADY_EXISTS_FOR_SELECTED_PROVIDER);
//            }
//        };
//    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAvailabilityPluginField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_AVAILABILITY_PLUGIN_FIELD, ChoiceType::class, [
            'label' => 'Availability Plugin',
            'placeholder' => 'Select one',
            'choices' => array_flip($options[ShipmentMethodFormDataProvider::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST]),
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPricePluginField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRICE_PLUGIN_FIELD, ChoiceType::class, [
            'label' => 'Price Plugin',
            'placeholder' => 'Select one',
            'choices' => array_flip($options[ShipmentMethodFormDataProvider::OPTION_PRICE_PLUGIN_CHOICE_LIST]),
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDeliveryTimePluginField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_DELIVERY_TIME_PLUGIN_FIELD, ChoiceType::class, [
            'label' => 'Delivery Time Plugin',
            'placeholder' => 'Select one',
            'choices' => array_flip($options[ShipmentMethodFormDataProvider::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST]),
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'required' => false,
        ]);

        return $this;
    }
}
