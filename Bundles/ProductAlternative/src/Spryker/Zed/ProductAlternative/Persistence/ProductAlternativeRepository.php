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
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativePersistenceFactory getFactory()
 */
class ProductAlternativeRepository extends AbstractRepository implements ProductAlternativeRepositoryInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        $productAlternativeEntities = $this->getFactory()
            ->createProductAlternativePropelQuery()
            ->filterByFkProduct($idProductConcrete)
            ->find();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeCollectionTransfer($productAlternativeEntities);
    }

    /**
     * @param int $idProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer|null
     */
    public function findProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer
    {
        $alternativeProductEntity = $this->getFactory()
            ->createProductAlternativePropelQuery()
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
     * @modules Product
     *
     * @param int[] $productIds
     *
     * @return bool
     */
    public function doAllConcreteProductsHaveAlternatives(array $productIds): bool
    {
        return ($this->getFactory()
            ->createProductAlternativePropelQuery()
            ->filterByFkProduct_In($productIds)
            ->groupByFkProduct()
            ->select(SpyProductAlternativeTableMap::COL_FK_PRODUCT)
            ->count() === count($productIds));
    }

    /**
     * {@inheritDoc}
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
        $productAbstractQuery = $this->prepareProductAbstractQuery($idProductAbstract, $localeTransfer);
        $productAbstractQuery->groupByIdProductAbstract()
            ->clearSelectColumns();
        $productAbstractData = $productAbstractQuery
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
            ->findOne();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAbstractDataToProductAlternativeListItemTransfer($productAbstractData);
    }

    /**
     * @modules Product
     * @modules Category
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
        $productConcreteQuery = $this->prepareProductQuery($idProduct, $localeTransfer);
        $productConcreteQuery->groupByIdProduct()
            ->clearSelectColumns();
        $productConcreteData = $productConcreteQuery->withColumn(SpyProductTableMap::COL_ID_PRODUCT, ProductAlternativeListItemTransfer::ID_PRODUCT)
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
            ->findOne();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductConcreteDataToProductAlternativeListItemTransfer($productConcreteData);
    }

    /**
     * @modules Product
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function prepareProductQuery(int $idProduct, LocaleTransfer $localeTransfer): SpyProductQuery
    {
        $productQuery = $this->getFactory()
            ->createProductPropelQuery();
        $productQuery->filterByIdProduct($idProduct)
            ->joinSpyProductLocalizedAttributes(null, Criteria::LEFT_JOIN)
            ->addJoinCondition(
                'SpyProductLocalizedAttributes',
                sprintf('%s = %s', SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, $localeTransfer->getIdLocale())
            );

        return $this->addCategoriesToProductQuery($productQuery, $localeTransfer);
    }

    /**
     * @modules Category
     * @modules ProductCategory
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function addCategoriesToProductQuery(SpyProductQuery $productQuery, LocaleTransfer $localeTransfer): SpyProductQuery
    {
        $productQuery
            ->useSpyProductAbstractQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                        ->joinAttribute(null, Criteria::LEFT_JOIN)
                        ->addJoinCondition(
                            'Attribute',
                            sprintf('%s = %s', SpyCategoryAttributeTableMap::COL_FK_LOCALE, $localeTransfer->getIdLocale())
                        )
                    ->endUse()
                ->endUse()
            ->endUse();

        return $productQuery;
    }

    /**
     * @modules Product
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function prepareProductAbstractQuery(int $idProductAbstract, LocaleTransfer $localeTransfer): SpyProductAbstractQuery
    {
        $productAbstractQuery = $this->getFactory()
            ->createProductAbstractPropelQuery();
        $productAbstractQuery->filterByIdProductAbstract($idProductAbstract)
            ->joinSpyProductAbstractLocalizedAttributes(null, Criteria::LEFT_JOIN)
            ->addJoinCondition(
                'SpyProductAbstractLocalizedAttributes',
                sprintf('%s = %s', SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE, $localeTransfer->getIdLocale())
            );

        return $this->addCategoriesToProductAbstractQuery($productAbstractQuery, $localeTransfer);
    }

    /**
     * @modules Category
     * @modules ProductCategory
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productAbstractQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function addCategoriesToProductAbstractQuery(SpyProductAbstractQuery $productAbstractQuery, LocaleTransfer $localeTransfer): SpyProductAbstractQuery
    {
        $productAbstractQuery
            ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->joinAttribute(null, Criteria::LEFT_JOIN)
                    ->addJoinCondition(
                        'Attribute',
                        sprintf('%s = %s', SpyCategoryAttributeTableMap::COL_FK_LOCALE, $localeTransfer->getIdLocale())
                    )
                ->endUse()
            ->endUse();

        return $productAbstractQuery;
    }

    /**
     * @modules Product
     *
     * @return int[]
     */
    public function findProductAbstractIdsWhichConcreteHasAlternative(): array
    {
        return $this->getFactory()
            ->createProductAlternativePropelQuery()
            ->useProductConcreteQuery()
                ->groupByFkProductAbstract()
            ->endUse()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();
    }
}
