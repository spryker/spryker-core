<?php

namespace SprykerFeature\Zed\Discount\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class DecisionRuleGrid extends AbstractGrid
{

    const NAME = 'name';
    const DECISION_RULE_PLUGIN = 'decision_rule_plugin';
    const VALUE = 'value';
    const DISCOUNT_NAME = 'discount_name';
    const DISCOUNT_AMOUNT = 'discount_amount';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),

            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::DECISION_RULE_PLUGIN)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::VALUE)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::DISCOUNT_NAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::DISCOUNT_AMOUNT)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }
}
