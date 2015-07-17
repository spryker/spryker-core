<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::PAC_PRODUCTSKU)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::PAC_STOCKNAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::QUANTITY)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::IS_NEVER_OUT_OF_STOCK)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
