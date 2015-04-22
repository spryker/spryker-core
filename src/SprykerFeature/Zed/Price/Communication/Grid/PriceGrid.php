<?php

namespace SprykerFeature\Zed\Price\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class PriceGrid extends AbstractGrid
{

    const PRICE = 'price';
    const ID_PRICE_PRODUCT = 'id_price_product';
    const PRICE_TYPE = 'price_type_name';
    const SKU = 'sku_product';
    const IS_ACTIVE = 'is_active';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->locator->ui()->pluginGridDefaultRowsRenderer(),
            $this->locator->ui()->pluginGridPagination(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::ID_PRICE_PRODUCT)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::SKU)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::PRICE)
                ->filterable()
                ->sortable(),
            $this->locator->ui()->pluginGridDefaultColumn()
                ->setName(self::PRICE_TYPE)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }
}
