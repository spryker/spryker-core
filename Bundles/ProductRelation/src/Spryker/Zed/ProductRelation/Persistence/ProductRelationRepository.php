<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence;

use Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductRelationCriteriaTransfer;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
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
    public function findProductRelationByCriteria(
        ProductRelationCriteriaTransfer $productRelationCriteriaTransfer
    ): ?ProductRelationTransfer {
        $productRelationQuery = $this->getFactory()
            ->createProductRelationQuery();
        $idProductAbstract = $productRelationCriteriaTransfer->getFkProductAbstract();
        $relationTypeKey = $productRelationCriteriaTransfer->getRelationTypeKey();
        $productRelationKey = $productRelationCriteriaTransfer->getProductRelationKey();

        if ($idProductAbstract !== null) {
            $productRelationQuery->filterByFkProductAbstract($idProductAbstract);
        }

        if ($relationTypeKey !== null) {
            $productRelationQuery
                ->useSpyProductRelationTypeQuery()
                    ->filterByKey($productRelationCriteriaTransfer->getRelationTypeKey())
                ->endUse();
        }

        if ($productRelationKey !== null) {
            $productRelationQuery->filterByProductRelationKey($productRelationKey);
        }

        $productRelationEntity = $productRelationQuery->findOne();

        if (!$productRelationEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductRelationMapper()
            ->mapProductRelationEntityToProductRelationTransfer($productRelationEntity, new ProductRelationTransfer());
    }

    /**
     * @module Product
     * @module Category
     * @module PriceProduct
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function getProductAbstractDataById(int $idProductAbstract, int $idLocale): array
    {
        return $this->getFactory()
            ->getProductRelationQueryContainer()
            ->queryProductsWithCategoriesByFkLocale($idLocale)
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer|null
     */
    public function findProductRelationById(int $idProductRelation): ?ProductRelationTransfer
    {
        $productRelationEntity = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByIdProductRelation($idProductRelation)
            ->findOne();

        if (!$productRelationEntity) {
            return null;
        }

        return $this->getFactory()
            ->createProductRelationMapper()
            ->mapProductRelationEntityToProductRelationTransfer(
                $productRelationEntity,
                new ProductRelationTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return int
     */
    public function getRelatedProductsCount(ProductRelationTransfer $productRelationTransfer): int
    {
        return $this->getFactory()->getProductRelationQueryContainer()
            ->getRulePropelQuery($productRelationTransfer)
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationCriteriaFilterTransfer $productRelationCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getRelatedProductsByCriteriaFilter(ProductRelationCriteriaFilterTransfer $productRelationCriteriaFilterTransfer): array
    {
        $relatedProducts = $this->getFactory()->getProductRelationQueryContainer()
            ->getRulePropelQuery($productRelationCriteriaFilterTransfer->getProductRelation())
            ->limit($productRelationCriteriaFilterTransfer->getLimit())
            ->offset($productRelationCriteriaFilterTransfer->getOffset())
            ->find();

        return $this->getFactory()
            ->createProductMapper()
            ->mapProductAbstractEntitiesToProductAbstractTransfers(
                $relatedProducts,
                []
            );
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdProductRelation(int $idProductRelation): StoreRelationTransfer
    {
        $productRelationStoreEntities = $this->getFactory()
            ->createProductRelationStoreQuery()
            ->filterByFkProductRelation($idProductRelation)
            ->leftJoinWithStore()
            ->find();

        $storeRelationTransfer = (new StoreRelationTransfer())->setIdEntity($idProductRelation);

        return $this->getFactory()
            ->createStoreRelationMapper()
            ->mapProductRelationStoreEntitiesToStoreRelationTransfer($productRelationStoreEntities, $storeRelationTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function getActiveProductRelations(): array
    {
        $productRelationEntities = $this->getFactory()
            ->getProductRelationQueryContainer()
            ->queryActiveAndScheduledRelations()
            ->find();

        if (!$productRelationEntities->getData()) {
            return [];
        }

        return $this->getFactory()
            ->createProductRelationMapper()
            ->mapProductRelationEntitiesToProductRelationTransfers($productRelationEntities, []);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationTypeTransfer[]
     */
    public function getProductRelationTypes(): array
    {
        $productRelationTypeEntities = $this->getFactory()
            ->createProductRelationTypeQuery()
            ->find();

        if ($productRelationTypeEntities->getData() === []) {
            return [];
        }

        return $this->getFactory()
            ->createProductRelationTypeMapper()
            ->mapProductRelationTypeEntitiesToProductRelationTypeTransfer(
                $productRelationTypeEntities,
                []
            );
    }

    /**
     * @param int[] $idProductAbstracts
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function getProductRelationsByProductAbstractIds(array $idProductAbstracts): array
    {
        $productRelationEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductAbstract_In($idProductAbstracts)
            ->leftJoinWithProductRelationStore()
            ->leftJoinWithSpyProductRelationProductAbstract()
            ->find();

        if ($productRelationEntities->getData() === []) {
            return [];
        }

        return $this->getFactory()
            ->createProductRelationMapper()
            ->mapProductRelationEntitiesToProductRelationTransfers(
                $productRelationEntities,
                []
            );
    }

    /**
     * @param int[] $productRelationIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductRelationIds(
        array $productRelationIds
    ): array {
        return $this->getFactory()
            ->createProductRelationQuery()
            ->filterByIdProductRelation_In($productRelationIds)
            ->select([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT,
            ])
            ->find()
            ->getData();
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer[]
     */
    public function findFilteredProductRelations(int $offset, int $limit): array
    {
        $productRelationEntities = $this->getFactory()
            ->createProductRelationQuery()
            ->setLimit($limit)
            ->setOffset($offset)
            ->find();

        return $this->getFactory()
            ->createProductRelationMapper()
            ->mapProductRelationEntitiesToProductRelationTransfers($productRelationEntities, []);
    }
}
