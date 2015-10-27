<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form\Transformers;

use SprykerFeature\Zed\Discount\Communication\Form\VoucherCodesType;
use SprykerFeature\Zed\Discount\DiscountConfig;
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
     * @var DiscountConfig
     */
    protected $config;

    /**
     * @var CamelCaseToUnderscore
     */
    protected $camelCaseToUnderscoreFilter;

    /**
     * @param DiscountConfig $config
     * @param CamelCaseToUnderscore $camelCaseToUnderscoreFilter
     */
    public function __construct(DiscountConfig $config, CamelCaseToUnderscore $camelCaseToUnderscoreFilter)
    {
        $this->config = $config;
        $this->camelCaseToUnderscoreFilter = $camelCaseToUnderscoreFilter;
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

        foreach ($formArray[VoucherCodesType::FIELD_DECISION_RULES] as $index => $fieldValue) {
            $fixedValueSet = [];
            foreach ($fieldValue as $key => $value) {
                $fixedValueSet[$this->camelCaseToSnakeCase($key)] = $value;
            }

            $formArray[VoucherCodesType::FIELD_DECISION_RULES][$index] = $fixedValueSet;
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
        $calculatorPlugins = $this->config->getAvailableCalculatorPlugins();

        $selectedCalculatorPlugin = $calculatorPlugins[$formArray[self::CALCULATOR_PLUGIN]];
        $formArray[self::AMOUNT] = $selectedCalculatorPlugin->$conversionMethod($formArray[self::AMOUNT]);

        return $formArray;
    }

    /**
     * @param array $fromArray
     * @param bool $showInForm
     *
     * @return array
     */
    protected function filterDecisionRules(array $fromArray, $showInForm = false)
    {
        $decisionRulesPlugins = $this->config->getAvailableDecisionRulePlugins();
        $conversionMethod = $this->decideTransformRule($showInForm);

        $decisionRulePluginKey = self::DECISION_RULE_PLUGIN_FORM;
        $valueKey = self::VALUE;

        if ($showInForm === false) {
            $decisionRulePluginKey = self::DECISION_RULE_PLUGIN_TRANSFER;
            $valueKey = self::VALUE_TRANSFER;
        }

        foreach ($fromArray[self::DECISION_RULES] as &$decisionRule) {
            if (!array_key_exists($decisionRulePluginKey, $decisionRule)) {
                continue;
            }
            $plugin = $decisionRulesPlugins[$decisionRule[$decisionRulePluginKey]];
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
        $collectorPlugins = $this->config->getAvailableCollectorPlugins();
        $conversionMethod = $this->decideTransformRule($showInForm);

        foreach ($formArray[self::COLLECTOR_PLUGINS] as &$collectorPlugin) {
            if (!array_key_exists(self::COLLECTOR_PLUGIN, $collectorPlugin) || !array_key_exists($collectorPlugin[self::COLLECTOR_PLUGIN], $collectorPlugins)) {
                continue;
            }
            $plugin = $collectorPlugins[$collectorPlugin[self::COLLECTOR_PLUGIN]];
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
