<?php

namespace Spryker\Zed\Discount\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

abstract class AbstractRuleForm extends AbstractForm
{

    const DECISION_RULES_PREFIX = 'PLUGIN_DECISION_RULE_';
    const DECISION_COLLECTOR_PREFIX = 'PLUGIN_COLLECTOR_';

    /**
     * @var DiscountCalculatorPluginInterface[]
     */
    protected $availableCalculatorPlugins;

    /**
     * @var DiscountCollectorPluginInterface[]
     */
    protected $availableCollectorPlugins;

    /**
     * @var DiscountDecisionRulePluginInterface[]
     */
    protected $availableDecisionRulePlugins;

    /**
     * @param DiscountCalculatorPluginInterface[] $availableCalculatorPlugins
     * @param DiscountCollectorPluginInterface[] $availableCollectorPlugins
     * @param DiscountDecisionRulePluginInterface[] $availableDecisionRulePlugins
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
