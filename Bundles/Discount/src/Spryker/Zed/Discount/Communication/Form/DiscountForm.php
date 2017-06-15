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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class DiscountForm extends AbstractType
{

    /**
     * @var \Symfony\Component\Form\FormTypeInterface|\Spryker\Zed\Discount\Communication\Form\GeneralForm
     */
    protected $generalForm;

    /**
     * @var \Symfony\Component\Form\FormTypeInterface|\Spryker\Zed\Discount\Communication\Form\CalculatorForm
     */
    protected $calculatorForm;

    /**
     * @var \Symfony\Component\Form\FormTypeInterface|\Spryker\Zed\Discount\Communication\Form\ConditionsForm
     */
    protected $conditionsForm;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface|\Spryker\Zed\Discount\Communication\Form\GeneralForm $generalForm
     * @param \Symfony\Component\Form\FormTypeInterface|\Spryker\Zed\Discount\Communication\Form\CalculatorForm $calculatorForm
     * @param \Symfony\Component\Form\FormTypeInterface|\Spryker\Zed\Discount\Communication\Form\ConditionsForm $conditionsForm
     */
    public function __construct(
        FormTypeInterface $generalForm,
        FormTypeInterface $calculatorForm,
        FormTypeInterface $conditionsForm
    ) {
        $this->generalForm = $generalForm;
        $this->calculatorForm = $calculatorForm;
        $this->conditionsForm = $conditionsForm;
    }

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
            $this->generalForm,
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
            $this->calculatorForm,
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
            $this->conditionsForm,
            [
                'data_class' => DiscountConditionTransfer::class,
                'label' => false,
            ]
        );

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'discount';
    }

}
