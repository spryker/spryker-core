<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Communication\Grid;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class PriceGrid extends AbstractTable
{

    const PRICE = 'price';
    const ID_PRICE_PRODUCT = 'id_price_product';
    const PRICE_TYPE = 'price_type_name';
    const SKU = 'sku_product';
    const IS_ACTIVE = 'is_active';

    /**
     * @return void
     */
    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

    /**
     * @return void
     */
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
                ->setName(self::ID_PRICE_PRODUCT)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::SKU)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::PRICE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::PRICE_TYPE)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
