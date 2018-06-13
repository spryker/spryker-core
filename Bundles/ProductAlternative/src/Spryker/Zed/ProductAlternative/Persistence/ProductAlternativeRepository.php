<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Shared\ProductAlternative\ProductAlternativeConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativePersistenceFactory getFactory()
 */
class ProductAlternativeRepository extends AbstractRepository implements ProductAlternativeRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idProductConcrete);

        $productAlternatives = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->find();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->hydrateProductAlternativeCollectionWithProductAlternatives($productAlternatives);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByIdProductAlternative($idProductAlternative);

        $alternativeProduct = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($alternativeProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idBaseProduct)
            ->filterByFkProductAbstractAlternative($idProductAbstract);

        $alternativeProduct = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($alternativeProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idBaseProduct)
            ->filterByFkProductConcreteAlternative($idProductConcrete);

        $alternativeProduct = $this->buildQueryFromCriteria($productAlternativeQuery)
            ->findOne();

        if (!$alternativeProduct) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($alternativeProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function getProductAlternativeListItemTransferForProductAbstract(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    ): ProductAlternativeListItemTransfer {
        $productAbstractTransfer
            ->requireIdProductAbstract()
            ->requireIsActive();

        $productAbstractData = $this->queryProductAlternative()
            ->filterByFkProductAbstractAlternative(
                $productAbstractTransfer->getIdProductAbstract()
            )
            ->innerJoinProductAbstractAlternative()
            ->useProductAbstractAlternativeQuery()
                ->innerJoinSpyProductAbstractLocalizedAttributes()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale(
                        $localeTransfer->getIdLocale()
                    )
                ->endUse()
                ->innerJoinSpyProductCategory()
                ->useSpyProductCategoryQuery()
                    ->innerJoinSpyCategory()
                    ->useSpyCategoryQuery()
                        ->innerJoinAttribute()
                        ->useAttributeQuery()
                            ->filterByFkLocale($localeTransfer->getIdLocale())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductAlternativeConstants::COL_ID)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, ProductAlternativeConstants::COL_SKU)
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, ProductAlternativeConstants::COL_NAME)
            ->withColumn('GROUP_CONCAT(' . SpyCategoryAttributeTableMap::COL_NAME . ')', ProductAlternativeConstants::COL_CATEGORIES)
            ->select([
                ProductAlternativeConstants::COL_ID,
                ProductAlternativeConstants::COL_SKU,
                ProductAlternativeConstants::COL_NAME,
                ProductAlternativeConstants::COL_CATEGORIES,
            ])
            ->groupByIdProductAlternative()
            ->distinct()
            ->findOne();

        $productAbstractData[ProductAlternativeConstants::COL_STATUS] = $productAbstractTransfer->getIsActive();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAbstractDataToProductAlternativeListItemTransfer($productAbstractData);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function getProductAlternativeListItemTransferForProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): ProductAlternativeListItemTransfer {
        $productConcreteTransfer->requireIdProductConcrete();

        $productConcreteData = $this->queryProductAlternative()
            ->filterByFkProductConcreteAlternative(
                $productConcreteTransfer->getIdProductConcrete()
            )
            ->useProductConcreteQuery()
                ->innerJoinSpyProductLocalizedAttributes()
                ->useSpyProductLocalizedAttributesQuery()
                    ->filterByFkLocale(
                        $localeTransfer->getIdLocale()
                    )
                ->endUse()
                ->innerJoinSpyProductAbstract()
                ->useSpyProductAbstractQuery()
                    ->innerJoinSpyProductCategory()
                    ->useSpyProductCategoryQuery()
                        ->innerJoinSpyCategory()
                        ->useSpyCategoryQuery()
                            ->innerJoinAttribute()
                            ->useAttributeQuery()
                                ->filterByFkLocale($localeTransfer->getIdLocale())
                            ->endUse()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductAlternativeConstants::COL_ID)
            ->withColumn(SpyProductTableMap::COL_SKU, ProductAlternativeConstants::COL_SKU)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductAlternativeConstants::COL_NAME)
            ->withColumn('GROUP_CONCAT(' . SpyCategoryAttributeTableMap::COL_NAME . ')', ProductAlternativeConstants::COL_CATEGORIES)
            ->withColumn(SpyProductTableMap::COL_IS_ACTIVE, ProductAlternativeConstants::COL_STATUS)
            ->select([
                ProductAlternativeConstants::COL_ID,
                ProductAlternativeConstants::COL_SKU,
                ProductAlternativeConstants::COL_NAME,
                ProductAlternativeConstants::COL_CATEGORIES,
                ProductAlternativeConstants::COL_STATUS,
            ])
            ->groupByIdProductAlternative()
            ->distinct()
            ->findOne();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductConcreteDataToProductAlternativeListItemTransfer($productConcreteData);
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function queryProductAlternative(): SpyProductAlternativeQuery
    {
        return $this->getFactory()
            ->createProductAlternativeQuery();
    }
}
