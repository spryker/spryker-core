<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class MethodForm extends AbstractType
{

    const FIELD_NAME_FIELD = 'name';
    const FIELD_ID_FIELD = 'idShipmentMethod';
    const FIELD_NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const FIELD_DESCRIPTION_GLOSSARY_FIELD = 'glossaryKeyDescription';
    const FIELD_IS_ACTIVE = 'isActive';
    const FIELD_PRICE_FIELD = 'price';
    const FIELD_AVAILABILITY_PLUGIN_FIELD = 'availabilityPlugin';
    const FIELD_TAX_PLUGIN_FIELD = 'taxCalculationPlugin';
    const FIELD_PRICE_CALCULATION_PLUGIN_FIELD = 'priceCalculationPlugin';
    const FIELD_DELIVERY_TIME_PLUGIN_FIELD = 'deliveryTimePlugin';
    const FIELD_CARRIER_FIELD = 'fkShipmentCarrier';
    const FIELD_TAX_SET = 'fkTaxSet';

    const OPTION_CARRIER_CHOICES = 'carrier_choices';
    const OPTION_TAX_SET_CHOICES = 'tax_set_choices';
    const OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST = 'availability_plugin_choice_list';
    const OPTION_PRICE_CALCULATION_PLUGIN_CHOICE_LIST = 'price_calculation_plugin_choice_list';
    const OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST = 'delivery_time_plugin_choice_list';
    const OPTION_TAX_PLUGIN_CHOICE_LIST = 'tax_plugin_choice_list';

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
        $builder
            ->add(self::FIELD_CARRIER_FIELD, 'choice', [
                'label' => 'Carrier',
                'placeholder' => 'Select one',
                'choices' => $options[self::OPTION_CARRIER_CHOICES],
                'constraints' => [
                    new NotBlank(),
                    new Required(),
                ],
            ])
            ->add(self::FIELD_NAME_FIELD, 'text', [
                'label' => 'Name',
                'constraints' => [
                    new NotBlank(),
                    new Required(),
                ],
            ])
            ->add(self::FIELD_NAME_GLOSSARY_FIELD, new AutosuggestType(), [
                'label' => 'Name glossary key',
                'url' => '/glossary/ajax/keys',
                'constraints' => [
                    new NotBlank(),
                    new Required(),
                ],
            ])
            ->add(self::FIELD_DESCRIPTION_GLOSSARY_FIELD, new AutosuggestType(), [
                'label' => 'Description glossary key',
                'url' => '/glossary/ajax/keys',
            ])
            ->add(self::FIELD_PRICE_FIELD, 'money', [
                'label' => 'Price',
            ])
            ->add(self::FIELD_AVAILABILITY_PLUGIN_FIELD, 'choice', [
                'label' => 'Availability Plugin',
                'placeholder' => 'Select one',
                'choice_list' => $options[self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST],
            ])
            ->add(self::FIELD_PRICE_CALCULATION_PLUGIN_FIELD, 'choice', [
                'label' => 'Price Calculation Plugin',
                'placeholder' => 'Select one',
                'choice_list' => $options[self::OPTION_PRICE_CALCULATION_PLUGIN_CHOICE_LIST],
            ])
            ->add(self::FIELD_DELIVERY_TIME_PLUGIN_FIELD, 'choice', [
                'label' => 'Delivery Time Plugin',
                'placeholder' => 'Select one',
                'choice_list' => $options[self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST],
            ])
            ->add(self::FIELD_TAX_PLUGIN_FIELD, 'choice', [
                'label' => 'Tax Calculation Plugin',
                'placeholder' => 'Select one',
                'choice_list' => $options[self::OPTION_TAX_PLUGIN_CHOICE_LIST],
            ])
            ->add(self::FIELD_TAX_SET, 'choice', [
                'label' => 'Tax Set',
                'placeholder' => 'Select one',
                'choices' => $options[self::OPTION_TAX_SET_CHOICES],
            ])
            ->add('isActive', 'checkbox')
            ->add(self::FIELD_ID_FIELD, 'hidden');
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_CARRIER_CHOICES);
        $resolver->setRequired(self::OPTION_TAX_SET_CHOICES);
        $resolver->setRequired(self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_PRICE_CALCULATION_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST);
        $resolver->setRequired(self::OPTION_TAX_PLUGIN_CHOICE_LIST);

        $resolver->setAllowedTypes(self::OPTION_AVAILABILITY_PLUGIN_CHOICE_LIST);
        $resolver->setAllowedTypes(self::OPTION_PRICE_CALCULATION_PLUGIN_CHOICE_LIST);
        $resolver->setAllowedTypes(self::OPTION_DELIVERY_TIME_PLUGIN_CHOICE_LIST);
        $resolver->setAllowedTypes(self::OPTION_TAX_PLUGIN_CHOICE_LIST);
    }

}
