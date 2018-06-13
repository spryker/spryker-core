<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductList;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductList\Persistence\Propel\AbstractSpyProductListQuery;

/**
 * @method \Spryker\Zed\ProductList\Persistence\Propel\AbstractSpyProductList[] runQuery(AbstractSpyProductListQuery $query, TableConfiguration $config, $returnRawResults = false)
 */
class ProductListTable extends AbstractTable
{
    protected const COLUMN_ID = SpyProductListTableMap::COL_ID_PRODUCT_LIST;
    protected const COLUMN_NAME = SpyProductListTableMap::COL_TITLE;

    /**
     * @var \Spryker\Zed\ProductList\Persistence\Propel\AbstractSpyProductListQuery
     */
    protected $productListQuery;

    public function __construct()
    {
        $this->productListQuery = new SpyProductListQuery();
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COLUMN_ID => 'ID',
            static::COLUMN_NAME => 'Name',
        ]);

        $config->setSortable([
            static::COLUMN_ID,
            static::COLUMN_NAME,
        ]);

        $config->setSearchable([
            static::COLUMN_ID,
            static::COLUMN_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $result = [];

        $queryResult = $this->runQuery($this->productListQuery, $config, true);
        foreach ($queryResult as $productListEntity) {
            $result[] = [
                static::COLUMN_ID => $productListEntity->getIdProductList(),
                static::COLUMN_NAME => $productListEntity->getTitle(),
            ];
        }

        return $result;
    }
}
