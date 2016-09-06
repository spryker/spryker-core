<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductManagement\Communication\Controller\EditController;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class VariantTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'product-variant-table';

    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_ID_PRODUCT = 'id_product';
    const COL_SKU = 'sku';
    const COL_STOCK = 'stock';
    const COL_STATUS = 'status';

    const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryQueryContainer;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param int $idProductAbstract
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer, $idProductAbstract)
    {
        $this->productQueryQueryContainer = $productQueryContainer;
        $this->idProductAbstract = $idProductAbstract;
        $this->defaultUrl = sprintf('variantTable?%s=%d', EditController::PARAM_ID_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_PRODUCT => 'Product ID',
            static::COL_SKU => 'Sku',
            static::COL_STOCK => 'Stock',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            static::COL_STATUS,
        ]);

        $config->setSearchable([
            SpyProductTableMap::COL_SKU,
        ]);

        $config->setSortable([
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyProductTableMap::COL_SKU,
            SpyProductTableMap::COL_IS_ACTIVE,
        ]);

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
            ->queryProduct()
            ->innerJoinSpyProductAbstract()
            ->filterByFkProductAbstract($this->idProductAbstract)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductTableMap::COL_SKU, static::COL_SKU)
            ->withColumn(SpyProductTableMap::COL_IS_ACTIVE, static::COL_STATUS)
        ;

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
            static::COL_ID_PRODUCT  => $item[SpyProductTableMap::COL_ID_PRODUCT],
            static::COL_SKU => $item[static::COL_SKU],
            static::COL_STOCK => 'Stock',
            static::COL_STATUS => $this->getStatusLabel($item[SpyProductTableMap::COL_IS_ACTIVE]),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($item)),
        ];
    }

    protected function getStatusLabel($status)
    {
        if (!$status) {
            return '<span class="label">Inactive</span>';
        }

        return '<span class="label-info">Active</span>';
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
            sprintf('/product-management/view/variant?%s=%d&%s=%d',
                EditController::PARAM_ID_PRODUCT,
                $item[SpyProductTableMap::COL_ID_PRODUCT],
                EditController::PARAM_ID_PRODUCT_ABSTRACT,
                $item[static::COL_ID_PRODUCT_ABSTRACT]
            ),
            'View'
        );

        $urls[] = $this->generateViewButton(
            sprintf('/product-management/edit/variant?%s=%d&%s=%d',
                EditController::PARAM_ID_PRODUCT,
                $item[SpyProductTableMap::COL_ID_PRODUCT],
                EditController::PARAM_ID_PRODUCT_ABSTRACT,
                $item[static::COL_ID_PRODUCT_ABSTRACT]
            ),
            'Edit'
        );

        return $urls;
    }

}
