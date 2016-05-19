<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\Transformers;

use Spryker\Zed\Discount\Communication\Form\VoucherCodesForm;
use Symfony\Component\Form\DataTransformerInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;

class DecisionRulesFormTransformer implements DataTransformerInterface
{

    const TRANSFORM_FROM_PERSISTENCE = 'transformFromPersistence';
    const TRANSFORM_FOR_PERSISTENCE = 'transformForPersistence';
    const VALUE = 'value';
    const VALUE_TRANSFER = 'Value';
    const DECISION_RULE_PLUGIN_FORM = 'decision_rule_plugin';
    const DECISION_RULE_PLUGIN_TRANSFER = 'DecisionRulePlugin';
    const CALCULATOR_PLUGIN = 'calculator_plugin';
    const AMOUNT = 'amount';
    const COLLECTOR_PLUGINS = 'collector_plugins';
    const COLLECTOR_PLUGIN = 'collector_plugin';
    const DECISION_RULES = 'decision_rules';

    /**
     * @var \Spryker\Zed\Discount\DiscountConfig
     */
    protected $config;

    /**
     * @var \Zend\Filter\Word\CamelCaseToUnderscore
     */
    protected $camelCaseToUnderscoreFilter;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    private $calculatorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[]
     */
    private $collectorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[]
     */
    private $decisionRulePlugins;

    /**
     * @param \Zend\Filter\Word\CamelCaseToUnderscore $camelCaseToUnderscoreFilter
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[] $collectorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     */
    public function __construct(
        CamelCaseToUnderscore $camelCaseToUnderscoreFilter,
        array $calculatorPlugins,
        array $collectorPlugins,
        array $decisionRulePlugins
    ) {
        $this->camelCaseToUnderscoreFilter = $camelCaseToUnderscoreFilter;
        $this->calculatorPlugins = $calculatorPlugins;
        $this->collectorPlugins = $collectorPlugins;
        $this->decisionRulePlugins = $decisionRulePlugins;
    }

    /**
     * @param array $formArray
     *
     * @return array
     */
    public function transform($formArray)
    {
        $formArray = $this->filterVoucherAmountValue($formArray);
        $formArray = $this->filterDecisionRules($formArray);
        $formArray = $this->filterCollectorPlugins($formArray);

        foreach ($formArray[VoucherCodesForm::FIELD_DECISION_RULES] as $index => $fieldValue) {
            $fixedValueSet = [];
            foreach ($fieldValue as $key => $value) {
                $fixedValueSet[$this->camelCaseToSnakeCase($key)] = $value;
            }

            $formArray[VoucherCodesForm::FIELD_DECISION_RULES][$index] = $fixedValueSet;
        }

        return $formArray;
    }

    /**
     * @param array $formArray
     *
     * @return array
     */
    public function reverseTransform($formArray)
    {
        $formArray = $this->filterVoucherAmountValue($formArray, true);
        $formArray = $this->filterDecisionRules($formArray, true);
        $formArray = $this->filterCollectorPlugins($formArray, true);

        return $formArray;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function camelCaseToSnakeCase($value)
    {
        $value = $this->camelCaseToUnderscoreFilter->filter($value);

        return $this->lowerCaseString($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function lowerCaseString($value)
    {
        return mb_convert_case($value, MB_CASE_LOWER, 'UTF-8');
    }

    /**
     * @param array $formArray
     * @param bool $showInForm
     *
     * @return array
     */
    protected function filterVoucherAmountValue($formArray, $showInForm = false)
    {
        if (!array_key_exists(self::CALCULATOR_PLUGIN, $formArray)) {
            return $formArray;
        }

        $conversionMethod = $this->decideTransformRule($showInForm);

        $formArray[self::AMOUNT] = $this->getAmount($formArray, $this->calculatorPlugins, $conversionMethod);

        return $formArray;
    }

    /**
     * @param array $formArray
     * @param array $calculatorPlugins
     * @param string $conversionMethod
     *
     * @return int
     */
    protected function getAmount(array $formArray, array $calculatorPlugins, $conversionMethod)
    {
        if ($formArray[self::CALCULATOR_PLUGIN] !== null) {
            $selectedCalculatorPlugin = $calculatorPlugins[$formArray[self::CALCULATOR_PLUGIN]];
            if ($selectedCalculatorPlugin !== null) {
                return $selectedCalculatorPlugin->$conversionMethod($formArray[self::AMOUNT]);
            }
        }

        return 0;
    }

    /**
     * @param array $fromArray
     * @param bool $showInForm
     *
     * @return array
     */
    protected function filterDecisionRules(array $fromArray, $showInForm = false)
    {
        $conversionMethod = $this->decideTransformRule($showInForm);

        $decisionRulePluginKey = self::DECISION_RULE_PLUGIN_FORM;
        $valueKey = self::VALUE;

        if ($showInForm === false) {
            $decisionRulePluginKey = self::DECISION_RULE_PLUGIN_TRANSFER;
            $valueKey = self::VALUE_TRANSFER;
        }

        foreach ($fromArray[self::DECISION_RULES] as &$decisionRule) {
            if (array_key_exists(self::DECISION_RULE_PLUGIN_FORM, $decisionRule)) {
                $decisionRulePluginKey = self::DECISION_RULE_PLUGIN_FORM;
                $valueKey = self::VALUE;
            }
            if (!array_key_exists($decisionRulePluginKey, $decisionRule)) {
                continue;
            }
            $plugin = $this->decisionRulePlugins[$decisionRule[$decisionRulePluginKey]];
            $decisionRule[self::VALUE] = $plugin->$conversionMethod($decisionRule[$valueKey]);
        }

        return $fromArray;
    }

    /**
     * @param array $formArray
     * @param bool $showInForm
     *
     * @return array
     */
    protected function filterCollectorPlugins(array $formArray, $showInForm = false)
    {
        $conversionMethod = $this->decideTransformRule($showInForm);

        foreach ($formArray[self::COLLECTOR_PLUGINS] as &$collectorPlugin) {
            if (!array_key_exists(self::COLLECTOR_PLUGIN, $collectorPlugin) ||
                !array_key_exists($collectorPlugin[self::COLLECTOR_PLUGIN], $this->collectorPlugins)) {
                continue;
            }
            $plugin = $this->collectorPlugins[$collectorPlugin[self::COLLECTOR_PLUGIN]];
            $collectorPlugin[self::VALUE] = $plugin->$conversionMethod($collectorPlugin[self::VALUE]);
        }

        return $formArray;
    }

    /**
     * @param bool $showInForm
     *
     * @return string
     */
    protected function decideTransformRule($showInForm)
    {
        if ($showInForm === true) {
            return self::TRANSFORM_FOR_PERSISTENCE;
        }

        return self::TRANSFORM_FROM_PERSISTENCE;
    }

}
