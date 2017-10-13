<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class MethodForm extends AbstractType
{
    const FIELD_NAME_FIELD = 'name';
    const FIELD_ID_FIELD = 'idShipmentMethod';
    const FIELD_IS_ACTIVE = 'isActive';
    const FIELD_DEFAULT_PRICE = 'defaultPrice';
    const FIELD_AVAILABILITY_PLUGIN_FIELD = 'availabilityPlugin';
    const FIELD_PRICE_PLUGIN_FIELD = 'pricePlugin';
    const FIELD_DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    const FIELD_CARRIER_FIELD = 'fkShipmentCarrier';
    const FIELD_TAX_SET_FIELD = 'fkTaxSet';

    const OPTION_CARRIER_CHOICES = 'carrier_choices';
    const OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST = 'availability_plugin_choice_list';
    const OPTION_PRICE_PLUGIN_CHOICE_LIST = 'price_plugin_choice_list';
    const OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST = 'delivery_time_plugin_choice_list';
    const OPTION_TAX_SETS = 'option_tax_sets';
    const OPTION_MONEY_FACADE = 'money facade';
    const OPTION_CURRENCY_ISO_CODE = 'currency_iso_code';

    /**
     * @return string
     */
    public function getName()
    {
        return 'method';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCarrierField($builder, $options)
            ->addNameField($builder)
            ->addDefaultPriceField($builder, $options)
            ->addAvailabilityPluginField($builder, $options)
            ->addPricePluginField($builder, $options)
            ->addDeliveryTimePluginField($builder, $options)
            ->addIsActiveField($builder)
            ->addIdField($builder)
            ->addTaxSetField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /** @var \Symfony\Component\OptionsResolver\OptionsResolver $resolver */
        $resolver->setRequired(self::OPTION_CARRIER_CHOICES);
        $resolver->setRequired(self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_PRICE_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_TAX_SETS);
        $resolver->setRequired(self::OPTION_MONEY_FACADE);

        $resolver->setAllowedTypes(self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST, 'array');
        $resolver->setAllowedTypes(self::OPTION_PRICE_PLUGIN_CHOICE_LIST, 'array');
        $resolver->setAllowedTypes(self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST, 'array');

        $resolver->setDefaults([
            static::OPTION_CURRENCY_ISO_CODE => null,
        ]);
    }

    /**
     * @deprecated Use `configureOptions()` instead.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCarrierField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_CARRIER_FIELD, 'choice', [
            'label' => 'Carrier',
            'placeholder' => 'Select one',
            'choices' => $options[self::OPTION_CARRIER_CHOICES],
            'constraints' => [
                new NotBlank(),
                new Required(),
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
    protected function addDefaultPriceField(FormBuilderInterface $builder, $options)
    {
        $fieldOptions = [
            'label' => 'Default price',
            'required' => false,
        ];

        if ($options[static::OPTION_CURRENCY_ISO_CODE] !== null) {
            $fieldOptions['currency'] = $options[static::OPTION_CURRENCY_ISO_CODE];
        }

        $builder->add(self::FIELD_DEFAULT_PRICE, 'money', $fieldOptions);

        $moneyFacade = $this->getMoneyFacade($options);

        $builder->get(self::FIELD_DEFAULT_PRICE)->addModelTransformer(new CallbackTransformer(
            function ($originalPrice) use ($moneyFacade) {
                if ($originalPrice === null) {
                    return $originalPrice;
                }

                return $moneyFacade->convertIntegerToDecimal($originalPrice);
            },
            function ($submittedPrice) use ($moneyFacade) {
                if ($submittedPrice === null) {
                    return $submittedPrice;
                }
                return $moneyFacade->convertDecimalToInteger($submittedPrice);
            }
        ));

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyInterface
     */
    protected function getMoneyFacade(array $options)
    {
        return $options[static::OPTION_MONEY_FACADE];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME_FIELD, 'text', [
            'label' => 'Name',
            'constraints' => [
                new NotBlank(),
                new Required(),
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
    protected function addAvailabilityPluginField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_AVAILABILITY_PLUGIN_FIELD, 'choice', [
            'label' => 'Availability Plugin',
            'placeholder' => 'Select one',
            'choices' => $options[self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST],
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
        $builder->add(self::FIELD_PRICE_PLUGIN_FIELD, 'choice', [
            'label' => 'Price Plugin',
            'placeholder' => 'Select one',
            'choices' => $options[self::OPTION_PRICE_PLUGIN_CHOICE_LIST],
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
        $builder->add(self::FIELD_DELIVERY_TIME_PLUGIN_FIELD, 'choice', [
            'label' => 'Delivery Time Plugin',
            'placeholder' => 'Select one',
            'choices' => $options[self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST],
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
        $builder->add(self::FIELD_IS_ACTIVE, 'checkbox', [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_FIELD, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTaxSetField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            self::FIELD_TAX_SET_FIELD,
            'choice',
            [
                'label' => 'Tax set',
                'choices' => $options[self::OPTION_TAX_SETS],
            ]
        );

        return $this;
    }
}
