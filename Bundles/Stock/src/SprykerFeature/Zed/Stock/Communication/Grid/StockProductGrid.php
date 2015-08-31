<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Communication\Grid;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class StockProductGrid extends AbstractTable
{

    const ID_STOCK_PRODUCT = 'id_stock_product';
    const PAC_PRODUCTSKU = 'sku';
    const PAC_STOCKNAME = 'name';
    const QUANTITY = 'quantity';
    const IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

    protected function prepareData(TableConfiguration $config)
    {
        // @todo: Implement prepareData() method.
    }

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
