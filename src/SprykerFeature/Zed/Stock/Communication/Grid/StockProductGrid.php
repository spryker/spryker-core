<?php

namespace SprykerFeature\Zed\Stock\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class StockProductGrid extends AbstractGrid
{

    const ID_STOCK_PRODUCT = 'id_stock_product';
    const PAC_PRODUCTSKU = 'sku';
    const PAC_STOCKNAME = 'name';
    const QUANTITY = 'quantity';
    const IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::PAC_PRODUCTSKU)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::PAC_STOCKNAME)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::QUANTITY)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::IS_NEVER_OUT_OF_STOCK)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }
}
