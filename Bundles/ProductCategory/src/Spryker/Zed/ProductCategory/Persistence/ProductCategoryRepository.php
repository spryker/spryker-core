<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    protected const COL_CATEGORY = 'category';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getProductAbstractCategoriesByIdProductAbstract(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer): array
    {
        $productAbstractTransfer->requireIdProductAbstract();

        return $this->queryProductCategoryMappings()
            ->filterByFkProductAbstract(
                $productAbstractTransfer->getIdProductAbstract()
            )
            ->innerJoinSpyCategory()
            ->useSpyCategoryQuery()
            ->innerJoinAttribute()
            ->useAttributeQuery()
            ->filterByFkLocale(
                $localeTransfer->getIdLocale()
            )
            ->endUse()
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, static::COL_CATEGORY)
            ->endUse()
            ->select(static::COL_CATEGORY)
            ->find()
            ->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getProductConcreteCategoriesByIdProductConcrete(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): array
    {
        $productConcreteTransfer->requireIdProductConcrete();

        $parentProductAbstract = $this
            ->queryProductCategoryMappings()
            ->innerJoinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->innerJoinSpyProduct()
                ->useSpyProductQuery()
                ->filterByIdProduct(
                    $productConcreteTransfer->getIdProductConcrete()
                )
                ->endUse()
            ->endUse()
            ->select(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find();

        return $this->getProductAbstractCategoriesByIdProductAbstract($parentProductAbstract, $localeTransfer);
    }

    /**
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    protected function queryProductCategoryMappings(): SpyProductCategoryQuery
    {
        return SpyProductCategoryQuery::create();
    }
}
