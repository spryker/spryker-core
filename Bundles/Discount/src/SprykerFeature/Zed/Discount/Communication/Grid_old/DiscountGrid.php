<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class DiscountGrid extends AbstractGrid
{

    const DISCOUNT_DISPLAY_NAME = 'display_name';
    const DISCOUNT_IS_ACTIVE = 'is_active';
    const DISCOUNT_IS_PRIVILEGED = 'is_privileged';
    const DISCOUNT_CALCULATOR_PLUGIN = 'calculator_plugin';
    const DISCOUNT_COLLECTOR_PLUGIN = 'collector_plugin';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_DISPLAY_NAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_CALCULATOR_PLUGIN)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_COLLECTOR_PLUGIN)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_IS_PRIVILEGED)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::DISCOUNT_IS_ACTIVE)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
