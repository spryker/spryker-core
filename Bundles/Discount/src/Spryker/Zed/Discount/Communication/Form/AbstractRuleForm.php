<?php

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Zed\Discount\DiscountConfig;
use Symfony\Component\Form\AbstractType;

abstract class AbstractRuleForm extends AbstractType
{

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';
    const DECISION_COLLECTOR_PREFIX = 'PLUGIN_COLLECTOR_';

    /**
     * @var array
     */
    protected $availableCalculatorPlugins;

    /**
     * @var array
     */
    protected $availableCollectorPlugins;

    /**
     * @var array
     */
    protected $availableDecisionRulePlugins;

    /**
     * @param \Spryker\Zed\Discount\DiscountConfig $discountConfig
     */
    public function __construct(DiscountConfig $discountConfig)
    {
        $this->availableCalculatorPlugins = $discountConfig->getAvailableCalculatorPlugins();
        $this->availableCollectorPlugins = $discountConfig->getAvailableCollectorPlugins();
        $this->availableDecisionRulePlugins = $discountConfig->getAvailableDecisionRulePlugins();
    }

    /**
     * @param string $decisionRuleName
     *
     * @return string
     */
    protected function filterChoicesLabels($decisionRuleName)
    {
        $decisionRuleName = str_replace(
            [self::DECISION_RULES_PREFIX, self::DECISION_COLLECTOR_PREFIX, '_'],
            ['', '', ' '],
            $decisionRuleName
        );

        return mb_convert_case($decisionRuleName, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @return array
     */
    protected function getAvailableCalculatorPlugins()
    {
        $plugins = [];
        $availablePlugins = array_keys($this->availableCalculatorPlugins);

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
    }

    /**
     * @return array
     */
    protected function getAvailableCollectorPlugins()
    {
        $plugins = [];
        $availablePlugins = array_keys($this->availableCollectorPlugins);

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
    }

    /**
     * @return array
     */
    protected function getAvailableDecisionRulePlugins()
    {
        $plugins = [];
        $availablePlugins = array_keys($this->availableDecisionRulePlugins);

        foreach ($availablePlugins as $plugin) {
            $plugins[$plugin] = $this->filterChoicesLabels($plugin);
        }

        return $plugins;
    }

    /**
     * @return array|string[]
     */
    protected function getCollectorLogicalOperators()
    {
        return [
            'AND' => 'AND',
            'OR' => 'OR',
        ];
    }

}
