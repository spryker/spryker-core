<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
class GlobalSoftThresholdType extends AbstractGlobalThresholdType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addSoftStrategiesField($builder, $options);
        $this->addSoftValueField($builder, $options);
        $this->addLocalizedForms($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(GlobalThresholdType::OPTION_CURRENCY_CODE);
        $resolver->setRequired(GlobalThresholdType::OPTION_SOFT_TYPES_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftStrategiesField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_STRATEGY, ChoiceType::class, [
            'choices' => $options[GlobalThresholdType::OPTION_SOFT_TYPES_ARRAY],
            'label' => false,
            'required' => false,
            'expanded' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftValueField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_THRESHOLD, MoneyType::class, [
            'label' => 'Enter minimum order value',
            'currency' => $options[GlobalThresholdType::OPTION_CURRENCY_CODE],
            'divisor' => 100,
            'required' => false,
        ]);

        return $this;
    }
}
