<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::NAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DECISION_RULE_PLUGIN)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::VALUE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_NAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_AMOUNT)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
