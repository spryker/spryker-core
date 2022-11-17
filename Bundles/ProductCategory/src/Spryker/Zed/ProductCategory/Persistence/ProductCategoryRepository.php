<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductCategory\Persistence\ProductCategoryPersistenceFactory getFactory()
 */
class ProductCategoryRepository extends AbstractRepository implements ProductCategoryRepositoryInterface
{
    /**
     * @var string
     */
    protected const TABLE_JOIN_CATEGORY = 'Category';

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, int $idLocale): CategoryCollectionTransfer
    {
        $spyCategoryCollection = $this->queryCategoriesByIdProductAbstract($idProductAbstract, $idLocale)->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($spyCategoryCollection, new CategoryCollectionTransfer());
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    protected function queryCategoriesByIdProductAbstract(int $idProductAbstract, int $idLocale): SpyProductCategoryQuery
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productCategoryQuery */
        $productCategoryQuery = $this->getFactory()
            ->createProductCategoryQuery()
            ->innerJoinWithSpyCategory()
            ->useSpyCategoryQuery()
                ->joinWithAttribute()
                ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
                ->addAnd(
                    SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                    $idLocale,
                    Criteria::EQUAL,
                )
                ->addAscendingOrderByColumn(SpyCategoryAttributeTableMap::COL_NAME)
            ->endUse();

        return $productCategoryQuery
            ->addAnd(
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                $idProductAbstract,
                Criteria::EQUAL,
            )
            ->groupByFkCategory()
            ->groupBy(SpyCategoryAttributeTableMap::COL_NAME);
    }

    /**
     * @module Product
     *
     * @param array<int> $categoryIds
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByCategoryIds(array $categoryIds): array
    {
        return $this->getFactory()
            ->createProductCategoryQuery()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->filterByFkCategory_In($categoryIds)
            ->useSpyProductAbstractQuery()
                ->innerJoinSpyProduct()
            ->endUse()
            ->find()
            ->toArray();
    }

    /**
     * @module Category
     * @module Locale
     *
     * @param \Generated\Shared\Transfer\ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryCollectionTransfer
     */
    public function getProductCategoryCollection(ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer): ProductCategoryCollectionTransfer
    {
        /** @var \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productCategoryQuery */
        $productCategoryQuery = $this->getFactory()
            ->createProductCategoryQuery()
            ->joinWithSpyCategory()
            ->useSpyCategoryQuery()
                ->joinWithNode()
                ->joinWithAttribute()
                ->useAttributeQuery()
                    ->joinWithLocale()
                ->endUse()
            ->endUse()
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        $productCategoryQuery = $this->applyProductCategoryFilters($productCategoryQuery, $productCategoryCriteriaTransfer);

        return $this->getFactory()
            ->createproductCategoryMapper()
            ->mapProductCategoryArrayToProductCategoryCollectionTransfer(
                $productCategoryQuery->find(),
                new ProductCategoryCollectionTransfer(),
            );
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery $productCategoryQuery
     * @param \Generated\Shared\Transfer\ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    protected function applyProductCategoryFilters(
        SpyProductCategoryQuery $productCategoryQuery,
        ProductCategoryCriteriaTransfer $productCategoryCriteriaTransfer
    ): SpyProductCategoryQuery {
        $productCategoryConditionsTransfer = $productCategoryCriteriaTransfer->getProductCategoryConditions();

        if (!$productCategoryConditionsTransfer) {
            return $productCategoryQuery;
        }

        if ($productCategoryConditionsTransfer->getProductAbstractIds()) {
            $productCategoryQuery->filterByFkProductAbstract_In($productCategoryConditionsTransfer->getProductAbstractIds());
        }

        if ($productCategoryConditionsTransfer->getLocaleIds()) {
            $productCategoryQuery
                ->useSpyCategoryQuery()
                    ->useAttributeQuery()
                        ->filterByFkLocale_In($productCategoryConditionsTransfer->getLocaleIds())
                    ->endUse()
                ->endUse();
        }

        return $productCategoryQuery;
    }
}
