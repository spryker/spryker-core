<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\MerchantRelationshipHardThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\MerchantRelationshipSoftThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence\MerchantRelationshipSalesOrderThresholdGuiRepositoryInterface getRepository()
 */
class MerchantRelationshipThresholdType extends AbstractType
{
    public const TYPE_NAME = 'merchant-relationship-threshold';

    public const FIELD_STORE_CURRENCY = 'storeCurrency';
    public const FIELD_ID_MERCHANT_RELATIONSHIP = 'idMerchantRelationship';
    public const FIELD_HARD = 'hardThreshold';
    public const FIELD_SOFT = 'softThreshold';

    public const OPTION_CURRENCY_CODE = 'option-currency-code';
    public const OPTION_STORE_CURRENCY_ARRAY = 'option-store-currency-array';
    public const OPTION_HARD_TYPES_ARRAY = 'option-hard-types-array';
    public const OPTION_SOFT_TYPES_ARRAY = 'option-soft-types-array';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreCurrencyField($builder, $options);
        $this->addIdMerchantRelationshipField($builder);
        $this->addHardThresholdForm($builder, $options);
        $this->addSoftThresholdForm($builder, $options);

        $this->addPluginForms($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_CURRENCY_CODE);
        $resolver->setRequired(static::OPTION_STORE_CURRENCY_ARRAY);
        $resolver->setRequired(static::OPTION_SOFT_TYPES_ARRAY);
        $resolver->setRequired(static::OPTION_HARD_TYPES_ARRAY);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStoreCurrencyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STORE_CURRENCY, Select2ComboBoxType::class, [
            'label' => 'Store and Currency',
            'choices' => $options[static::OPTION_STORE_CURRENCY_ARRAY],
            'placeholder' => false,
            'multiple' => false,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdMerchantRelationshipField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_MERCHANT_RELATIONSHIP, HiddenType::class, [
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addHardThresholdForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_HARD, MerchantRelationshipHardThresholdType::class, [
            static::OPTION_HARD_TYPES_ARRAY => $options[static::OPTION_HARD_TYPES_ARRAY],
            static::OPTION_CURRENCY_CODE => $options[static::OPTION_CURRENCY_CODE],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftThresholdForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SOFT, MerchantRelationshipSoftThresholdType::class, [
            static::OPTION_SOFT_TYPES_ARRAY => $options[static::OPTION_SOFT_TYPES_ARRAY],
            static::OPTION_CURRENCY_CODE => $options[static::OPTION_CURRENCY_CODE],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addPluginForms(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        foreach ($this->getFactory()->getSalesOrderThresholdFormExpanderPlugins() as $formExpanderPlugin) {
            if ($formExpanderPlugin->getThresholdGroup() === MerchantRelationshipSalesOrderThresholdGuiConfig::GROUP_SOFT) {
                $formExpanderPlugin->expand($builder->get(static::FIELD_SOFT), $options);
                continue;
            }

            $formExpanderPlugin->expand($builder->get(static::FIELD_HARD), $options);
        }

        return $builder;
    }
}
