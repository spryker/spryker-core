<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 */
class MethodForm extends AbstractType
{
    const FIELD_NAME_FIELD = 'name';
    const FIELD_ID_FIELD = 'idShipmentMethod';
    const FIELD_IS_ACTIVE = 'isActive';
    const FIELD_AVAILABILITY_PLUGIN_FIELD = 'availabilityPlugin';
    const FIELD_PRICE_PLUGIN_FIELD = 'pricePlugin';
    const FIELD_DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    const FIELD_CARRIER_FIELD = 'fkShipmentCarrier';
    const FIELD_TAX_SET_FIELD = 'fkTaxSet';
    const FIELD_PRICES = 'prices';

    const OPTION_CARRIER_CHOICES = 'carrier_choices';
    const OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST = 'availability_plugin_choice_list';
    const OPTION_PRICE_PLUGIN_CHOICE_LIST = 'price_plugin_choice_list';
    const OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST = 'delivery_time_plugin_choice_list';
    const OPTION_TAX_SETS = 'option_tax_sets';
    const OPTION_MONEY_FACADE = 'money facade';
    const OPTION_DATA_CLASS = 'data_class';

    /**
     * @return string
     */
    public function getBlockPrefix()
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
            ->addAvailabilityPluginField($builder, $options)
            ->addPricePluginField($builder, $options)
            ->addDeliveryTimePluginField($builder, $options)
            ->addIsActiveField($builder)
            ->addIdField($builder)
            ->addTaxSetField($builder, $options)
            ->addMoneyCollectionField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_CARRIER_CHOICES);
        $resolver->setRequired(self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_PRICE_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_TAX_SETS);
        $resolver->setRequired(self::OPTION_MONEY_FACADE);

        $resolver->setAllowedTypes(self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST, 'array');
        $resolver->setAllowedTypes(self::OPTION_PRICE_PLUGIN_CHOICE_LIST, 'array');
        $resolver->setAllowedTypes(self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST, 'array');
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCarrierField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_CARRIER_FIELD, ChoiceType::class, [
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
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME_FIELD, TextType::class, [
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
     *
     * @return $this
     */
    protected function addMoneyCollectionField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PRICES,
            $this->getFactory()->getMoneyCollectionFormTypePlugin()->getType(),
            [
                ShipmentConstants::OPTION_AMOUNT_PER_STORE => true,
                'required' => false,
            ]
        );

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
        $builder->add(self::FIELD_AVAILABILITY_PLUGIN_FIELD, ChoiceType::class, [
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
        $builder->add(self::FIELD_PRICE_PLUGIN_FIELD, ChoiceType::class, [
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
        $builder->add(self::FIELD_DELIVERY_TIME_PLUGIN_FIELD, ChoiceType::class, [
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
        $builder->add(self::FIELD_IS_ACTIVE, CheckboxType::class, [
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
        $builder->add(self::FIELD_ID_FIELD, HiddenType::class);

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
            ChoiceType::class,
            [
                'label' => 'Tax set',
                'choices' => $options[self::OPTION_TAX_SETS],
            ]
        );

        return $this;
    }
}
