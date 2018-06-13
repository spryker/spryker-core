<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductList\Persistence\Propel\AbstractSpyProductList;

/**
 * @uses ProductList
 */
class ProductListTable extends AbstractTable
{
    protected const COLUMN_ID = SpyProductListTableMap::COL_ID_PRODUCT_LIST;
    protected const COLUMN_NAME = SpyProductListTableMap::COL_TITLE;
    protected const COLUMN_TYPE = SpyProductListTableMap::COL_TYPE;
    protected const COLUMN_BUTTONS = 'COLUMN_BUTTONS';

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
            static::COLUMN_TYPE => 'Type',
            static::COLUMN_BUTTONS => 'Actions',
        ]);

        $config->setSortable([
            static::COLUMN_ID,
            static::COLUMN_NAME,
            static::COLUMN_TYPE,
        ]);

        $config->setSearchable([
            static::COLUMN_ID,
            static::COLUMN_NAME,
        ]);
        $config->addRawColumn(static::COLUMN_BUTTONS);

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
            $result[] = $this->prepareDataRow($productListEntity);
        }

        return $result;
    }

    /**
     * @param \Spryker\Zed\ProductList\Persistence\Propel\AbstractSpyProductList $productListEntity
     *
     * @return string[]
     */
    protected function prepareDataRow(AbstractSpyProductList $productListEntity): array
    {
        $typeString = $productListEntity->getType() == SpyProductListTableMap::COL_TYPE_BLACKLIST
            ? 'Blacklist'
            : 'Whitelist';

        return [
            static::COLUMN_ID => $productListEntity->getIdProductList(),
            static::COLUMN_NAME => $productListEntity->getTitle(),
            static::COLUMN_TYPE => $typeString,
            static::COLUMN_BUTTONS => $this->createViewButton($productListEntity),
        ];
    }

    /**
     * @param \Spryker\Zed\ProductList\Persistence\Propel\AbstractSpyProductList $productListEntity
     *
     * @return string
     */
    protected function createViewButton(AbstractSpyProductList $productListEntity)
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton('edit', 'Edit');
        $buttons[] = $this->generateRemoveButton('delete', 'Delete');

        return implode(' ', $buttons);
    }
}
