<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstract(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProduct(): SpyProductQuery
    {
        return SpyProductQuery::create();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithName(int $idLocale): SpyProductAbstractQuery
    {
        return $this->queryProductAbstract()
            ->useSpyProductAbstractLocalizedAttributesQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function queryProductConcreteWithName(int $idLocale): SpyProductQuery
    {
        return $this->queryProduct()
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME);
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
    public function filterProductAbstractBySku(string $sku, int $limit): array
    {
        $productAbstractEntities = $this
            ->queryProductAbstract()
            ->filterBySku_Like('%' . $sku . '%')
            ->limit($limit)
            ->find();

        $abstractProducts = [];

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity */
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
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, int $limit): array
    {
        $localeTransfer->requireIdLocale();

        $productAbstractEntities = $this
            ->queryProductAbstractWithName(
                $localeTransfer->getIdLocale()
            )
            ->useSpyProductAbstractLocalizedAttributesQuery()
            ->filterByName_Like('%' . $localizedName . '%')
            ->endUse()
            ->limit($limit)
            ->find();

        $abstractProducts = [];

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity */
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
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteBySku(string $sku, int $limit): array
    {
        $productConcreteEntities = $this
            ->queryProduct()
            ->filterBySku_Like('%' . $sku . '%')
            ->limit($limit)
            ->find();

        $concreteProducts = [];

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity */
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
     * @param int $limit
     *
     * @return array
     */
    public function filterProductConcreteByLocalizedName(LocaleTransfer $localeTransfer, string $localizedName, int $limit): array
    {
        $localeTransfer->requireIdLocale();

        $productConcreteEntities = $this
            ->queryProductConcreteWithName(
                $localeTransfer->getIdLocale()
            )
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByName_Like('%' . $localizedName . '%')
            ->endUse()
            ->limit($limit)
            ->find();

        $concreteProducts = [];

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity */
        foreach ($productConcreteEntities as $productConcreteEntity) {
            $concreteProducts[] = [
                ProductConstants::KEY_FILTERED_PRODUCTS_CONCRETE_ID => $productConcreteEntity->getIdProduct(),
                ProductConstants::KEY_FILTERED_PRODUCTS_RESULT => $productConcreteEntity
                    ->getVirtualColumn(ProductConstants::COL_FILTERED_PRODUCTS_PRODUCT_NAME),
            ];
        }

        return $concreteProducts;
    }
}
