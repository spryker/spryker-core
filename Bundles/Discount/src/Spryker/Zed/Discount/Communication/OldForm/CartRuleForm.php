<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CartRuleForm extends AbstractRuleForm
{

    const FIELD_DISPLAY_NAME = 'display_name';
    const FIELD_DESCRIPTION = 'description';
    const FIELD_AMOUNT = 'amount';
    const FIELD_VALID_FROM = 'valid_from';
    const FIELD_VALID_TO = 'valid_to';
    const FIELD_IS_PRIVILEGED = 'is_privileged';
    const FIELD_IS_ACTIVE = 'is_active';
    const FIELD_CALCULATOR_PLUGIN = 'calculator_plugin';
    const FIELD_COLLECTOR_PLUGINS = 'collector_plugins';
    const FIELD_DECISION_RULES = 'decision_rules';
    const FIELD_COLLECTOR_LOGICAL_OPERATOR = 'collector_logical_operator';

    const VALID_FROM = 'valid_from';
    const VALID_TO = 'valid_to';

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins;

    /**
     * @var \Symfony\Component\Form\DataTransformerInterface
     */
    protected $decisionRulesFormTransformer;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[]
     */
    protected $collectorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[]
     */
    protected $decisionRulePlugins;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[] $collectorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     * @param \Symfony\Component\Form\DataTransformerInterface $decisionRulesFormTransformer
     */
    public function __construct(array $calculatorPlugins, array $collectorPlugins, array $decisionRulePlugins, DataTransformerInterface $decisionRulesFormTransformer)
    {
        parent::__construct($calculatorPlugins, $collectorPlugins, $decisionRulePlugins);

        $this->calculatorPlugins = $calculatorPlugins;
        $this->collectorPlugins = $collectorPlugins;
        $this->decisionRulePlugins = $decisionRulePlugins;
        $this->decisionRulesFormTransformer = $decisionRulesFormTransformer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cart_rule';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addDisplayNameField($builder)
            ->addDescriptionField($builder)
            ->addAmountField($builder)
            ->addCalculatorPluginField($builder)
            ->addCollectorPluginsField($builder)
            ->addCollectorLogicalOperatorField($builder)
            ->addValidFromField($builder)
            ->addValidToField($builder)
            ->addIsPrivilegedField($builder)
            ->addIsActiveField($builder)
            ->addDecisionRulesField($builder);

        $builder->addModelTransformer($this->decisionRulesFormTransformer);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDisplayNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_DISPLAY_NAME, 'text', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_DESCRIPTION, 'textarea');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAmountField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_AMOUNT, 'text', [
            'label' => 'Amount',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALID_FROM, 'date');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_VALID_TO, 'date');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsPrivilegedField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_PRIVILEGED, 'checkbox', [
            'label' => 'Is Combinable with other discounts',
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
            'label' => 'Is Active',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCalculatorPluginField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CALCULATOR_PLUGIN, 'choice', [
            'label' => 'Calculator Plugin',
            'choices' => $this->getAvailableCalculatorPlugins(),
            'empty_data' => null,
            'required' => false,
            'placeholder' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCollectorPluginsField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COLLECTOR_PLUGINS, 'collection', [
            'type' => new CollectorPluginForm(
                $this->calculatorPlugins,
                $this->collectorPlugins,
                $this->decisionRulePlugins
            ),
            'allow_add' => true,
            'allow_delete' => true,
            'allow_extra_fields' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDecisionRulesField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_DECISION_RULES, 'collection', [
            'type' => new DecisionRuleForm(
                $this->calculatorPlugins,
                $this->collectorPlugins,
                $this->decisionRulePlugins
            ),
            'allow_add' => true,
            'allow_delete' => true,
            'allow_extra_fields' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCollectorLogicalOperatorField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COLLECTOR_LOGICAL_OPERATOR, 'choice', [
            'label' => 'Logical operator for combining multiple collectors',
            'choices' => $this->getCollectorLogicalOperators(),
            'required' => true,
        ]);

        return $this;
    }

}
