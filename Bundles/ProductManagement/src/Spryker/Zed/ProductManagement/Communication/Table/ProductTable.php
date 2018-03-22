<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Controller\EditController;
use Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelperInterface;

class ProductTable extends AbstractProductTable
{
    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_NAME = 'name';
    const COL_SKU = 'sku';
    const COL_TAX_SET = 'tax_set';
    const COL_VARIANT_COUNT = 'variants';
    const COL_STATUS = 'status';
    const COL_ACTIONS = 'actions';
    const COL_IS_BUNDLE = 'is_bundle';
    const COL_STORE_RELATION = 'store_relation';
    const COL_PRODUCT_TYPE = 'product_type';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelperInterface
     */
    protected $productTypeHelper;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\ProductManagement\Communication\Helper\ProductTypeHelperInterface $productTypeHelper
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        LocaleTransfer $localeTransfer,
        ProductTypeHelperInterface $productTypeHelper
    ) {
        $this->productQueryQueryContainer = $productQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->productTypeHelper = $productTypeHelper;
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
            static::COL_TAX_SET => 'Tax Set',
            static::COL_VARIANT_COUNT => 'Variants',
            static::COL_STATUS => 'Status',
            static::COL_IS_BUNDLE => 'Contains bundles',
            static::COL_PRODUCT_TYPE => 'Product type',
            static::COL_STORE_RELATION => 'Stores',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_STATUS,
            static::COL_IS_BUNDLE,
            static::COL_PRODUCT_TYPE,
            static::COL_STORE_RELATION,
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            SpyTaxSetTableMap::COL_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU,
            static::COL_NAME,
            static::COL_TAX_SET,
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
            ->useSpyProductAbstractLocalizedAttributesQuery()
            ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(SpyTaxSetTableMap::COL_NAME, static::COL_TAX_SET);

        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productAbstractEntity) {
            $productAbstractCollection[] = $this->generateItem($productAbstractEntity);
        }

        return $productAbstractCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function generateItem(SpyProductAbstract $productAbstractEntity)
    {
        return [
            static::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            static::COL_SKU => $productAbstractEntity->getSku(),
            static::COL_NAME => $productAbstractEntity->getVirtualColumn(static::COL_NAME),
            static::COL_TAX_SET => $productAbstractEntity->getVirtualColumn(static::COL_TAX_SET),
            static::COL_VARIANT_COUNT => $productAbstractEntity->getSpyProducts()->count(),
            static::COL_STATUS => $this->getAbstractProductStatusLabel($productAbstractEntity),
            static::COL_IS_BUNDLE => $this->getIsBundleProductLable($productAbstractEntity),
            static::COL_PRODUCT_TYPE => $this->getTypeName($productAbstractEntity),
            static::COL_STORE_RELATION => $this->getStoreNames($productAbstractEntity->getIdProductAbstract()),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($productAbstractEntity)),
        ];
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getStoreNames($idProductAbstract)
    {
        $productAbstractStoreCollection = $this->getProductAbstractStoreWithStore($idProductAbstract);

        $storeNames = [];
        foreach ($productAbstractStoreCollection as $productAbstractStoreEntity) {
            $storeNames[] = sprintf(
                '<span class="label label-info">%s</span>',
                $productAbstractStoreEntity->getSpyStore()->getName()
            );
        }

        return implode(" ", $storeNames);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractStore[]
     */
    protected function getProductAbstractStoreWithStore($idProductAbstract)
    {
        return $this->productQueryQueryContainer->queryProductAbstractStoreWithStoresByFkProductAbstract($idProductAbstract);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getTypeName(SpyProductAbstract $productAbstractEntity)
    {
        if ($this->productTypeHelper->isProductBundleByProductAbstractEntity($productAbstractEntity)) {
            return 'Product Bundle';
        }

        if ($this->productTypeHelper->isGiftCardByProductAbstractEntity($productAbstractEntity)) {
            return 'Gift card';
        }

        return 'Product';
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $item
     *
     * @return array
     */
    protected function createActionColumn(SpyProductAbstract $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-management/view', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item->getIdProductAbstract(),
            ]),
            'View'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-management/edit', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item->getIdProductAbstract(),
            ]),
            'Edit'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-attribute-gui/view/productAbstract', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item->getIdProductAbstract(),
            ]),
            'Manage Attributes'
        );

        return $urls;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getAbstractProductStatusLabel(SpyProductAbstract $productAbstractEntity)
    {
        $isActive = false;
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getIsActive()) {
                $isActive = true;
            }
        }

        return $this->getStatusLabel($isActive);
    }

    /**
     * @deprecated Use ProductTypeHelperInterface::isProductBundleByProductAbstractEntity() instead
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getIsBundleProductLable(SpyProductAbstract $productAbstractEntity)
    {
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getSpyProductBundlesRelatedByFkProduct()->count() > 0) {
                return 'Yes';
            }
        }

        return 'No';
    }
}
