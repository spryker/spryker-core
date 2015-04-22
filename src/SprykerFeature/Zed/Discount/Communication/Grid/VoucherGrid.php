<?php

namespace SprykerFeature\Zed\Discount\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class VoucherGrid extends AbstractGrid
{

    const VOUCHER_POOL = 'voucher_pool';
    const IS_ACTIVE = 'is_active';
    const CODE = 'code';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),

            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::VOUCHER_POOL)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::CODE)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::VOUCHER_POOL)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridBooleanColumn()
                ->setName(self::IS_ACTIVE)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }
}
