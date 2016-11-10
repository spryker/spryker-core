<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductManagement\Communication\Controller\EditController;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class VariantTable extends AbstractProductTable
{

    const TABLE_IDENTIFIER = 'product-variant-table';

    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_ID_PRODUCT = 'id_product';
    const COL_SKU = 'sku';
    const COL_NAME = 'name';
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
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    private $localeTransfer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer, $idProductAbstract, LocaleTransfer $localeTransfer)
    {
        $this->productQueryQueryContainer = $productQueryContainer;
        $this->idProductAbstract = $idProductAbstract;
        $this->localeTransfer = $localeTransfer;
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
            static::COL_NAME => 'Name',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            static::COL_STATUS,
        ]);

        $config->setSearchable([
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT,
            static::COL_SKU,
            static::COL_NAME,
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
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->filterByFkProductAbstract($this->idProductAbstract)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_NAME);

        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productEntity) {
            $productAbstractCollection[] = $this->generateItem($productEntity);
        }

        return $productAbstractCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array
     */
    protected function generateItem(SpyProduct $productEntity)
    {
        return [
            static::COL_ID_PRODUCT => $productEntity->getIdProduct(),
            static::COL_SKU => $productEntity->getSku(),
            static::COL_NAME => $productEntity->getVirtualColumn(static::COL_NAME),
            static::COL_STATUS => $this->getStatusLabel($productEntity->getIsActive()),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($productEntity)),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array
     */
    protected function createActionColumn(SpyProduct $productEntity)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            sprintf(
                '/product-management/view/variant?%s=%d&%s=%d',
                EditController::PARAM_ID_PRODUCT,
                $productEntity->getIdProduct(),
                EditController::PARAM_ID_PRODUCT_ABSTRACT,
                $productEntity->getFkProductAbstract()
            ),
            'View'
        );

        $urls[] = $this->generateEditButton(
            sprintf(
                '/product-management/edit/variant?%s=%d&%s=%d',
                EditController::PARAM_ID_PRODUCT,
                $productEntity->getIdProduct(),
                EditController::PARAM_ID_PRODUCT_ABSTRACT,
                $productEntity->getFkProductAbstract()
            ),
            'Edit'
        );

        return $urls;
    }

}
