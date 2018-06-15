<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
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
        $productAlternativeEntities = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idProductConcrete)
            ->find();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeCollectionTransfer($productAlternativeEntities);
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
        $alternativeProductEntity = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByIdProductAlternative($idProductAlternative)
            ->findOne();

        if (!$alternativeProductEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransfer($alternativeProductEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function getProductAlternativeListItemTransferForProductAbstract(
        int $idProductAbstract,
        LocaleTransfer $localeTransfer
    ): ProductAlternativeListItemTransfer {
        $productAbstractData = $this->getFactory()
            ->createProductAbstractQuery()
            ->filterByIdProductAbstract($idProductAbstract)
            ->leftJoinSpyProductAbstractLocalizedAttributes()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale(
                    $localeTransfer->getIdLocale()
                )
            ->endUse()
            ->leftJoinSpyProductCategory()
            ->useSpyProductCategoryQuery()
                ->leftJoinSpyCategory()
                ->useSpyCategoryQuery()
                    ->innerJoinAttribute()
                    ->useAttributeQuery()
                        ->filterByFkLocale($localeTransfer->getIdLocale())
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, ProductAlternativeListItemTransfer::ID_PRODUCT)
            ->withColumn(SpyProductAbstractTableMap::COL_SKU, ProductAlternativeListItemTransfer::SKU)
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, ProductAlternativeListItemTransfer::NAME)
            ->withColumn('GROUP_CONCAT(' . SpyCategoryAttributeTableMap::COL_NAME . ')', ProductAlternativeListItemTransfer::CATEGORIES)
            ->select([
                 ProductAlternativeListItemTransfer::ID_PRODUCT,
                 ProductAlternativeListItemTransfer::SKU,
                 ProductAlternativeListItemTransfer::NAME,
                 ProductAlternativeListItemTransfer::CATEGORIES,
            ])
            ->groupByIdProductAbstract()
            ->findOne();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAbstractDataToProductAlternativeListItemTransfer($productAbstractData);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    public function getProductAlternativeListItemTransferForProductConcrete(
        int $idProduct,
        LocaleTransfer $localeTransfer
    ): ProductAlternativeListItemTransfer {
        $productConcreteData = $this->getFactory()
            ->createProductQuery()
            ->filterByIdProduct($idProduct)
            ->leftJoinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale(
                    $localeTransfer->getIdLocale()
                )
            ->endUse()
            ->leftJoinSpyProductAbstract()
            ->useSpyProductAbstractQuery()
                ->leftJoinSpyProductCategory()
                ->useSpyProductCategoryQuery()
                    ->leftJoinSpyCategory()
                    ->useSpyCategoryQuery()
                        ->leftJoinAttribute()
                        ->useAttributeQuery()
                            ->filterByFkLocale($localeTransfer->getIdLocale())
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductAlternativeListItemTransfer::ID_PRODUCT)
            ->withColumn(SpyProductTableMap::COL_SKU, ProductAlternativeListItemTransfer::SKU)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, ProductAlternativeListItemTransfer::NAME)
            ->withColumn('GROUP_CONCAT(' . SpyCategoryAttributeTableMap::COL_NAME . ')', ProductAlternativeListItemTransfer::CATEGORIES)
            ->withColumn(SpyProductTableMap::COL_IS_ACTIVE, ProductAlternativeListItemTransfer::STATUS)
            ->select([
                ProductAlternativeListItemTransfer::ID_PRODUCT,
                ProductAlternativeListItemTransfer::SKU,
                ProductAlternativeListItemTransfer::NAME,
                ProductAlternativeListItemTransfer::CATEGORIES,
                ProductAlternativeListItemTransfer::STATUS,
            ])
            ->groupByIdProduct()
            ->findOne();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductConcreteDataToProductAlternativeListItemTransfer($productConcreteData);
    }
}
