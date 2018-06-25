<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Table;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductList;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantRelationshipProductListGui\Communication\Controller\RoutingConstantsInterface;
use Spryker\Zed\ProductListGui\Communication\Controller\DeleteController;
use Spryker\Zed\ProductListGui\Communication\Controller\EditController;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @see \Spryker\Zed\ProductListGui\Communication\Table\ProductListTable as source of origin
 * @uses ProductList
 */
class ProductListTable extends AbstractTable
{
    protected const COLUMN_ID = SpyProductListTableMap::COL_ID_PRODUCT_LIST;
    protected const COLUMN_NAME = SpyProductListTableMap::COL_TITLE;
    protected const COLUMN_TYPE = SpyProductListTableMap::COL_TYPE;
    protected const COLUMN_BUTTONS = 'COLUMN_BUTTONS';

    /**
     * @var \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected $productListQuery;

    public function __construct()
    {
        $this->productListQuery = (new SpyProductListQuery())
            ->filterByFkMerchantRelationship(null, Criteria::NOT_EQUAL);
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
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity
     *
     * @return string[]
     */
    protected function prepareDataRow(SpyProductList $productListEntity): array
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
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity
     *
     * @return string
     */
    protected function createViewButton(SpyProductList $productListEntity)
    {
        $buttons = [];

        $editUrl = Url::generate(RoutingConstantsInterface::PRODUCT_LIST_EDIT, [
            EditController::PARAM_ID_PRODUCT_LIST => $productListEntity->getIdProductList(),
        ]);
        $buttons[] = $this->generateEditButton($editUrl, 'Edit');

        $deleteUrl = Url::generate(RoutingConstantsInterface::PRODUCT_LIST_DELETE, [
            DeleteController::PARAM_ID_PRODUCT_LIST => $productListEntity->getIdProductList(),
        ]);
        $buttons[] = $this->generateRemoveButton($deleteUrl, 'Delete');

        return implode(' ', $buttons);
    }
}
