<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductManagement\Communication\Controller\EditController;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductTable extends AbstractTable
{

    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_NAME = 'name';
    const COL_SKU = 'sku';
    const COL_PRICE = 'price';
    const COL_TAX_SET = 'tax_set';
    const COL_VARIANT_COUNT = 'variants';
    const COL_STOCK = 'stock';
    const COL_CATEGORIES = 'categories';
    const COL_AVAILABILITY = 'availability';
    const COL_STATUS = 'status';

    const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_PRODUCT_ABSTRACT => 'Product ID',
            static::COL_NAME => 'Name',
            static::COL_SKU => 'Sku',
            static::COL_PRICE => 'Price',
            static::COL_TAX_SET => 'Tax Set',
            static::COL_VARIANT_COUNT => 'Variants',
            static::COL_STOCK => 'Stock',
            static::COL_CATEGORIES => 'Categories',
            static::COL_AVAILABILITY => 'Availability',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
        ]);

        $config->setSortable([
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractTableMap::COL_SKU,
        ]);

        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productQueryQueryContainer
            ->queryProductAbstract()
            ->innerJoinSpyTaxSet()
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::COL_SKU)
            ->withColumn(SpyProductAbstractTableMap::COL_FK_TAX_SET, static::COL_TAX_SET)
            ->withColumn(SpyTaxSetTableMap::COL_NAME, static::COL_TAX_SET);

        $queryResults = $this->runQuery($query, $config);

        $productAbstractCollection = [];
        foreach ($queryResults as $itemData) {
            $productAbstractCollection[] = $this->generateItem($itemData);
        }

        return $productAbstractCollection;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function generateItem(array $item)
    {
        return [
            static::COL_ID_PRODUCT_ABSTRACT  => $item[static::COL_ID_PRODUCT_ABSTRACT],
            static::COL_SKU => $item[static::COL_SKU],
            static::COL_NAME => 'Name',
            static::COL_PRICE => 'Price',
            static::COL_TAX_SET => $item[static::COL_TAX_SET],
            static::COL_VARIANT_COUNT => 'Variants',
            static::COL_STOCK => 'Stock',
            static::COL_CATEGORIES => 'Categories',
            static::COL_AVAILABILITY => 'Availability',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($item)),
        ];
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionColumn(array $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-management/view', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item[static::COL_ID_PRODUCT_ABSTRACT],
            ]),
            'View'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-management/edit', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item[static::COL_ID_PRODUCT_ABSTRACT],
            ]),
            'Edit'
        );

        return $urls;
    }

}
