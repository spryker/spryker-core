<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Table;

use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StockTable extends AbstractTable
{
    public const COL_ID_STOCK = 'id_stock';
    public const COL_NAME = 'name';

    public const IDENTIFIER = 'stock_data_table';

    /**
     * @var \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected $stockQuery;

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery
     */
    public function __construct(SpyStockQuery $stockQuery)
    {
        $this->stockQuery = $stockQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_STOCK => 'Warehouse ID',
            static::COL_NAME => 'Name',
        ]);

        $config->setSortable([
            static::COL_ID_STOCK,
            static::COL_NAME,
        ]);
        $config->setSearchable([
            static::COL_NAME,
        ]);
        $config->setDefaultSortField(static::COL_ID_STOCK);
        $this->setTableIdentifier(static::IDENTIFIER);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResult = $this->runQuery($this->stockQuery, $config, true);

        $stockCollection = [];
        foreach ($queryResult as $stockEntity) {
            $stockCollection[] = $this->generateItem($stockEntity);
        }

        return $stockCollection;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     *
     * @return array
     */
    protected function generateItem(SpyStock $stockEntity): array
    {
        return [
            static::COL_ID_STOCK => $stockEntity->getIdStock(),
            static::COL_NAME => $stockEntity->getName(),
        ];
    }
}
