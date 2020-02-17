<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductSelectorTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory getFactory()
 */
class ProductRelationRepository extends AbstractRepository implements ProductRelationRepositoryInterface
{
    protected const COL_IS_ACTIVE_AGGREGATION = 'is_active_aggregation';
    protected const COL_ASSIGNED_CATEGORIES = 'assignedCategories';

    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findUniqueProductRelation(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): ?ProductRelationTransfer {
        $productRelationCriteriaTransfer->requireFkProductAbstract()
            ->requireRelationTypeKey();
        $productRelationEntity = $this->getFactory()
            ->createProductRelationQuery()
            ->useSpyProductRelationTypeQuery()
                ->filterByKey($productRelationCriteriaTransfer->getRelationTypeKey())
            ->endUse()
            ->filterByFkProductAbstract($productRelationCriteriaTransfer->getFkProductAbstract())
            ->findOne();

        if (!$productRelationEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductRelationMapper()
            ->mapProductRelationEntityToProductRelationTransfer($productRelationEntity, new ProductRelationTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer[]
     */
    public function findProductAttributes(): array
    {
        $productAttributeKeyEntities = $this->getFactory()
            ->getProductAttributeQuery()
            ->find();

        if ($productAttributeKeyEntities->getData() === []) {
            return [];
        }

        return $this->getFactory()
            ->createProductAttributeMapper()
            ->mapProductAttributeKeyEntitiesToProductAttributeKeyTransfers(
                $productAttributeKeyEntities,
                []
            );
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductSelectorTransfer
     */
    public function findProductWithCategoriesByFkLocale(int $idProductAbstract, int $idLocale): ProductSelectorTransfer
    {
        $productSelectorTransfer = new ProductSelectorTransfer();
        $productAbstractEntity = $this->getFactory()
            ->getProductAbstractQuery()
            ->leftJoinSpyProduct()
            ->select([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                SpyProductAbstractLocalizedAttributesTableMap::COL_DESCRIPTION,
                SpyPriceProductTableMap::COL_PRICE,
                SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL,
            ])
            ->withColumn(
                sprintf(
                    'GROUP_CONCAT(%s)',
                    SpyCategoryAttributeTableMap::COL_NAME
                ),
                static::COL_ASSIGNED_CATEGORIES
            )
            ->leftJoinPriceProduct()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($idLocale)
            ->endUse()
            ->leftJoinSpyProductCategory()
            ->useSpyProductImageSetQuery()
                ->filterByFkLocale($idLocale)
                ->_or()
                ->filterByFkLocale(null)
                ->useSpyProductImageSetToProductImageQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinWithSpyProductImage()
                ->endUse()
            ->endUse()
            ->addJoin(
                [SpyProductCategoryTableMap::COL_FK_CATEGORY, $idLocale],
                [SpyCategoryAttributeTableMap::COL_FK_CATEGORY, SpyCategoryAttributeTableMap::COL_FK_LOCALE],
                Criteria::LEFT_JOIN
            )
            ->filterByIdProductAbstract($idProductAbstract)
            ->withColumn(
                'GROUP_CONCAT(' . SpyProductTableMap::COL_IS_ACTIVE . ')',
                static::COL_IS_ACTIVE_AGGREGATION
            )
            ->addGroupByColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->addGroupByColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME)
            ->addGroupByColumn(SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->addGroupByColumn(SpyPriceProductTableMap::COL_PRICE)
            ->findOne();

        if (!$productAbstractEntity) {
            return $productSelectorTransfer;
        }

        return $this->getFactory()
            ->createProductMapper()
            ->mapProductArrayToProductSelectorTransfer($productAbstractEntity, $productSelectorTransfer);
    }
}
