<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 */
class DiscountForm extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addGeneralSubForm($builder)
            ->addCalculatorSubForm($builder)
            ->addConditionsSubForm($builder);

        $this->executeFormTypeExpanderPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function executeFormTypeExpanderPlugins(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->getFactory()->getDiscountFormTypeExpanderPlugins() as $calculatorFormTypeExpanderPlugin) {
            $calculatorFormTypeExpanderPlugin->expandFormType($builder, $options);
        }

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGeneralSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountConfiguratorTransfer::DISCOUNT_GENERAL,
            GeneralForm::class,
            [
                'data_class' => DiscountGeneralTransfer::class,
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCalculatorSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR,
            CalculatorForm::class,
            [
                'data_class' => DiscountCalculatorTransfer::class,
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addConditionsSubForm(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountConfiguratorTransfer::DISCOUNT_CONDITION,
            ConditionsForm::class,
            [
                'data_class' => DiscountConditionTransfer::class,
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'discount';
    }
}
