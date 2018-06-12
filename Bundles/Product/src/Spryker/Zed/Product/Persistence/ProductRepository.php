<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function getProductAbstractDataBySku(string $sku, int $limit): array
    {
        $productAbstractQuery = $this->queryProductAbstract()
            ->filterBySku_Like('%' . $sku . '%')
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT)
            ->limit($limit)
            ->select([ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT]);

        return $productAbstractQuery->find()
            ->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function getProductAbstractDataByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, int $limit): array
    {
        $localeTransfer->requireIdLocale();

        $productAbstractQuery = $this->queryProductAbstractWithName($localeTransfer->getIdLocale())
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByName_Like('%' . $localizedName . '%')
            ->endUse()
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID)
            ->withColumn(ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT)
            ->limit($limit)
            ->select([ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT]);

        return $productAbstractQuery->find()
            ->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function getProductConcreteDataBySku(string $sku, int $limit): array
    {
        $productQuery = $this->queryProduct()
            ->filterBySku_Like('%' . $sku . '%')
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID)
            ->withColumn(SpyProductTableMap::COL_SKU, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT)
            ->limit($limit)
            ->select([ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT]);

        return $productQuery->find()
            ->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function getProductConcreteDataByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, int $limit): array
    {
        $localeTransfer->requireIdLocale();

        $productQuery = $this->queryProductConcreteWithName($localeTransfer->getIdLocale())
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByName_Like('%' . $localizedName . '%')
            ->endUse()
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID)
            ->withColumn(ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT)
            ->limit($limit)
            ->select([ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID, ProductConstants::KEY_FILTERED_PRODUCTS_RESULT]);

        return $productQuery->find()
            ->toArray();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryProductAbstract(): SpyProductAbstractQuery
    {
        return $this->getFactory()
            ->createProductAbstractQuery();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function queryProduct(): SpyProductQuery
    {
        return $this->getFactory()
            ->createProductQuery();
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryProductAbstractWithName(int $idLocale): SpyProductAbstractQuery
    {
        return $this->queryProductAbstract()
            ->innerJoinSpyProductAbstractLocalizedAttributes()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME);
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function queryProductConcreteWithName(int $idLocale): SpyProductQuery
    {
        return $this->queryProduct()
            ->innerJoinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME);
    }
}
