<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalHardMaximumThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalHardThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\GlobalSoftThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
class GlobalThresholdType extends AbstractType
{
    /**
     * @var string
     */
    public const TYPE_NAME = 'global-threshold';

    /**
     * @var string
     */
    public const FIELD_STORE_CURRENCY = 'storeCurrency';

    /**
     * @var string
     */
    public const FIELD_HARD = 'hardThreshold';

    /**
     * @var string
     */
    public const FIELD_HARD_MAXIMUM = 'hardMaximumThreshold';

    /**
     * @var string
     */
    public const FIELD_SOFT = 'softThreshold';

    /**
     * @var string
     */
    public const OPTION_CURRENCY_CODE = 'option-currency-code';

    /**
     * @var string
     */
    public const OPTION_STORE_CURRENCY_ARRAY = 'option-store-currency-array';

    /**
     * @var string
     */
    public const OPTION_HARD_TYPES_ARRAY = 'option-hard-types-array';

    /**
     * @var string
     */
    public const OPTION_SOFT_TYPES_ARRAY = 'option-soft-types-array';

    /**
     * @var string
     */
    public const OPTION_HARD_MAXIMUM_TYPES_ARRAY = 'option-hard-maximum-types-array';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreCurrencyField($builder, $options);
        $this->addHardThresholdForm($builder, $options);
        $this->addSoftThresholdForm($builder, $options);
        $this->addHardMaximumThresholdForm($builder, $options);

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
        $resolver->setRequired(static::OPTION_HARD_TYPES_ARRAY);
        $resolver->setRequired(static::OPTION_SOFT_TYPES_ARRAY);
        $resolver->setRequired(static::OPTION_HARD_MAXIMUM_TYPES_ARRAY);
    }

    /**
     * @param string $prefix
     * @param string $localeCode
     *
     * @return string
     */
    public static function getLocalizedFormName(string $prefix, string $localeCode): string
    {
        return $prefix . '_' . $localeCode;
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
     * @param array $options
     *
     * @return $this
     */
    protected function addHardThresholdForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_HARD, GlobalHardThresholdType::class, [
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
    protected function addHardMaximumThresholdForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_HARD_MAXIMUM, GlobalHardMaximumThresholdType::class, [
            static::OPTION_HARD_MAXIMUM_TYPES_ARRAY => $options[static::OPTION_HARD_MAXIMUM_TYPES_ARRAY],
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
        $builder->add(static::FIELD_SOFT, GlobalSoftThresholdType::class, [
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
            if ($formExpanderPlugin->getThresholdGroup() === SalesOrderThresholdGuiConfig::GROUP_SOFT) {
                $formExpanderPlugin->expand($builder->get(static::FIELD_SOFT), $options);

                continue;
            }

            if ($formExpanderPlugin->getThresholdGroup() === SalesOrderThresholdGuiConfig::GROUP_HARD_MAX) {
                $formExpanderPlugin->expand($builder->get(static::FIELD_HARD_MAXIMUM), $options);

                continue;
            }

            $formExpanderPlugin->expand($builder->get(static::FIELD_HARD), $options);
        }

        return $builder;
    }
}
