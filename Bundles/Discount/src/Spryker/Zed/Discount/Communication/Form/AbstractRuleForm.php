<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Symfony\Component\Form\AbstractType;

abstract class AbstractRuleForm extends AbstractType
{

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';
    const DECISION_COLLECTOR_PREFIX = 'PLUGIN_COLLECTOR_';

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $availableCalculatorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[]
     */
    protected $availableCollectorPlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[]
     */
    protected $availableDecisionRulePlugins;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $availableCalculatorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface[] $availableCollectorPlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface[] $availableDecisionRulePlugins
     */
    public function __construct(
        array $availableCalculatorPlugins,
        array $availableCollectorPlugins,
        array $availableDecisionRulePlugins
    ) {
        $this->availableCalculatorPlugins = $availableCalculatorPlugins;
        $this->availableCollectorPlugins = $availableCollectorPlugins;
        $this->availableDecisionRulePlugins = $availableDecisionRulePlugins;
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
     * @return string[]
     */
    protected function getCollectorLogicalOperators()
    {
        return [
            'AND' => 'AND',
            'OR' => 'OR',
        ];
    }

}
