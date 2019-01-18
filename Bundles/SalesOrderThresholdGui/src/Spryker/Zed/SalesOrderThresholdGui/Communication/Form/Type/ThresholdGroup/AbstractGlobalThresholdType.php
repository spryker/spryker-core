<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\Communication\SalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
abstract class AbstractGlobalThresholdType extends AbstractType
{
    public const FIELD_ID_THRESHOLD = 'idThreshold';
    public const FIELD_STRATEGY = 'strategy';
    public const FIELD_THRESHOLD = 'threshold';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(GlobalThresholdType::OPTION_CURRENCY_CODE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addStrategyField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_STRATEGY, ChoiceType::class, [
            'label' => false,
            'choices' => $choices,
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
    protected function addThresholdValueField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_THRESHOLD, MoneyType::class, [
            'label' => 'Enter threshold value',
            'currency' => $options[GlobalThresholdType::OPTION_CURRENCY_CODE],
            'divisor' => 100,
            'constraints' => [
                new Range(['min' => 0]),
                $this->getFactory()->createThresholdStrategyConstraint(),
            ],
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $this->addLocalizedForm($builder, $localeTransfer->getLocaleName());
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    protected function addLocalizedForm(FormBuilderInterface $builder, string $name, array $options = [])
    {
        $builder->add($name, LocalizedMessagesType::class, [
            'label' => false,
        ]);

        return $this;
    }
}
