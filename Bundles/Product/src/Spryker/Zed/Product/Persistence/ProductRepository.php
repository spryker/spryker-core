<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
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
     * @param null|int $limit
     *
     * @return array
     */
    public function filterProductAbstractBySku(string $sku, ?int $limit = null): array
    {
        $limit = $limit ?? $this->getFactory()
                ->getConfig()
                ->getFilteredProductsLimitDefault();

        $productAbstractEntities = $this->queryProductAbstract()
            ->filterBySku_Like('%' . $sku . '%')
            ->limit($limit)
            ->find();

        $abstractProducts = [];

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $abstractProducts[] = [
                ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID => $productAbstractEntity->getIdProductAbstract(),
                ProductConstants::KEY_FILTERED_PRODUCTS_RESULT => $productAbstractEntity->getSku(),
            ];
        }

        return $abstractProducts;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $localizedName
     * @param null|int $limit
     *
     * @return array
     */
    public function filterProductAbstractByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, ?int $limit = null): array
    {
        $limit = $limit ?? $this->getFactory()
                ->getConfig()
                ->getFilteredProductsLimitDefault();

        $localeTransfer->requireIdLocale();

        $productAbstractEntities = $this->queryProductAbstractWithNameAndCategory(
            $localeTransfer->getIdLocale()
        )
            ->useSpyProductAbstractLocalizedAttributesQuery()
            ->filterByName_Like('%' . $localizedName . '%')
            ->endUse()
            ->limit($limit)
            ->find();

        $abstractProducts = [];

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $abstractProducts[] = [
                ProductConstants::KEY_FILTERED_PRODUCTS_ABSTRACT_ID => $productAbstractEntity->getIdProductAbstract(),
                ProductConstants::KEY_FILTERED_PRODUCTS_RESULT => $productAbstractEntity
                    ->getVirtualColumn(ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME),
            ];
        }

        return $abstractProducts;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param null|int $limit
     *
     * @return array
     */
    public function filterProductConcreteBySku(string $sku, ?int $limit = null): array
    {
        $limit = $limit ?? $this->getFactory()
                ->getConfig()
                ->getFilteredProductsLimitDefault();

        $productConcreteEntities = $this->queryProduct()
            ->filterBySku_Like('%' . $sku . '%')
            ->limit($limit)
            ->find();

        $concreteProducts = [];

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $concreteProducts[] = [
                ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID => $productConcreteEntity->getIdProduct(),
                ProductConstants::KEY_FILTERED_PRODUCTS_RESULT => $productConcreteEntity->getSku(),
            ];
        }

        return $concreteProducts;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $localizedName
     * @param null|int $limit
     *
     * @return array
     */
    public function filterProductConcreteByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, ?int $limit = null): array
    {
        $limit = $limit ?? $this->getFactory()
                ->getConfig()
                ->getFilteredProductsLimitDefault();

        $localeTransfer->requireIdLocale();

        $productConcreteEntities = $this->queryProductConcreteWithNameAndCategory(
            $localeTransfer->getIdLocale()
        )
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByName_Like('%' . $localizedName . '%')
            ->endUse()
            ->limit($limit)
            ->find();

        $concreteProducts = [];

        foreach ($productConcreteEntities as $productConcreteEntity) {
            $concreteProducts[] = [
                ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID => $productConcreteEntity->getIdProduct(),
                ProductConstants::KEY_FILTERED_PRODUCTS_RESULT => $productConcreteEntity
                    ->getVirtualColumn(ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME),
            ];
        }

        return $concreteProducts;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryProductAbstract(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function queryProduct(): SpyProductQuery
    {
        return SpyProductQuery::create();
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryProductAbstractWithNameAndCategory(int $idLocale): SpyProductAbstractQuery
    {
        return $this->queryProductAbstract()
            ->innerJoinSpyProductCategory()
            ->useSpyProductCategoryQuery()
                ->innerJoinSpyCategory()
                ->useSpyCategoryQuery()
                    ->innerJoinAttribute()
                    ->useAttributeQuery()
                        ->filterByFkLocale($idLocale)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_CATEGORY)
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
    protected function queryProductConcreteWithNameAndCategory(int $idLocale): SpyProductQuery
    {
        return $this->queryProduct()
            ->innerJoinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->innerJoinSpyProductCategory()
                ->useSpyProductCategoryQuery()
                    ->innerJoinSpyCategory()
                    ->useSpyCategoryQuery()
                        ->innerJoinAttribute()
                        ->useAttributeQuery()
                            ->filterByFkLocale($idLocale)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_CATEGORY)
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME);
    }
}
