<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedPersistenceFactory getFactory()
 */
class ProductDiscontinuedRepository extends AbstractRepository implements ProductDiscontinuedRepositoryInterface
{
    use InstancePoolingTrait;

    /**
     * @module Product
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer|null
     */
    public function findProductDiscontinuedByProductId(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer
    ): ?ProductDiscontinuedTransfer {
        $productDiscontinuedEntity = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->leftJoinWithSpyProductDiscontinuedNote()
            ->leftJoinWithProduct()
            ->filterByFkProduct($productDiscontinuedTransfer->getFkProduct())
            ->find();

        if ($productDiscontinuedEntity->count()) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapProductDiscontinuedEntityToProductDiscontinuedTransfer(
                    $productDiscontinuedEntity->getFirst(),
                    new ProductDiscontinuedTransfer(),
                );
        }

        return null;
    }

    /**
     * @param array $productIds
     *
     * @return bool
     */
    public function areAllConcreteProductsDiscontinued(array $productIds): bool
    {
        return ($this->getFactory()
                ->createProductDiscontinuedQuery()
                ->filterByFkProduct_In($productIds)
                ->count() === count($productIds));
    }

    /**
     * @param array<int> $productIds
     *
     * @return bool
     */
    public function isAnyProductConcreteDiscontinued(array $productIds): bool
    {
        return $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByFkProduct_In($productIds)
            ->exists();
    }

    /**
     * @param int $batchSize
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductsToDeactivate(int $batchSize = 1000): ProductDiscontinuedCollectionTransfer
    {
        $isPoolingEnabled = $this->isInstancePoolingEnabled();
        if ($isPoolingEnabled) {
            $this->disableInstancePooling();
        }

        $productDiscontinuedEntityCollection = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->joinWithProduct()
            ->filterByActiveUntil(['max' => time()], Criteria::LESS_THAN)
            ->limit($batchSize)
            ->find();

        if ($productDiscontinuedEntityCollection->count()) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapProductDiscontinuedEntitiesToProductDiscontinuedCollectionTransfer(
                    $productDiscontinuedEntityCollection,
                    new ProductDiscontinuedCollectionTransfer(),
                );
        }

        if ($isPoolingEnabled) {
            $this->enableInstancePooling();
        }

        return new ProductDiscontinuedCollectionTransfer();
    }

    /**
     * @module Product
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function findProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaFilterTransfer $criteriaFilterTransfer
    ): ProductDiscontinuedCollectionTransfer {
        $productDiscontinuedQuery = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->leftJoinWithSpyProductDiscontinuedNote()
            ->leftJoinWithProduct();

        if ($criteriaFilterTransfer->getIds()) {
            $productDiscontinuedQuery
                ->filterByIdProductDiscontinued_In($criteriaFilterTransfer->getIds());
        }

        if ($criteriaFilterTransfer->getSkus()) {
            $productDiscontinuedQuery
                ->useProductQuery()
                    ->filterBySku_In($criteriaFilterTransfer->getSkus())
                ->endUse();
        }

        $productDiscontinuedEntityCollection = $productDiscontinuedQuery->find();

        if ($productDiscontinuedEntityCollection->count()) {
            return $this->getFactory()
                ->createProductDiscontinuedMapper()
                ->mapProductDiscontinuedEntitiesToProductDiscontinuedCollectionTransfer(
                    $productDiscontinuedEntityCollection,
                    new ProductDiscontinuedCollectionTransfer(),
                );
        }

        return new ProductDiscontinuedCollectionTransfer();
    }

    /**
     * @module Product
     *
     * @param array<string> $skus
     *
     * @return array<string>
     */
    public function getDiscontinuedProductSkus(array $skus): array
    {
        return $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->joinWithProduct()
                ->select(SpyProductTableMap::COL_SKU)
            ->useProductQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @module Product
     *
     * @return array<int>
     */
    public function findProductAbstractIdsWithDiscontinuedConcrete(): array
    {
        return $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->leftJoinProduct()
            ->useProductQuery()
                ->groupByFkProductAbstract()
            ->endUse()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT])
            ->find()
            ->toArray();
    }

    /**
     * @uses Product
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function getProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
    ): ProductDiscontinuedCollectionTransfer {
        $productDiscontinuedCollectionTransfer = new ProductDiscontinuedCollectionTransfer();
        $productDiscontinuedQuery = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->joinWithProduct();

        $productDiscontinuedQuery = $this->applyProductDiscontinuedConditions($productDiscontinuedCriteriaTransfer, $productDiscontinuedQuery);
        $paginationTransfer = $productDiscontinuedCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $productDiscontinuedQuery = $this->applyProductDiscontinuedPagination($paginationTransfer, $productDiscontinuedQuery);
            $productDiscontinuedCollectionTransfer->setPagination($paginationTransfer);
        }

        $productDiscontinuedEntities = $productDiscontinuedQuery->find();

        if ($productDiscontinuedCriteriaTransfer->getWithProductDiscontiniuedNotes()) {
            $this->expandWithProductDiscontinuedNotes($productDiscontinuedEntities);
        }

        return $this->getFactory()
            ->createProductDiscontinuedMapper()
            ->mapProductDiscontinuedEntitiesToProductDiscontinuedCollectionTransfer(
                $productDiscontinuedEntities,
                $productDiscontinuedCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery $productDiscontinuedQuery
     *
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery
     */
    protected function applyProductDiscontinuedConditions(
        ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer,
        SpyProductDiscontinuedQuery $productDiscontinuedQuery
    ): SpyProductDiscontinuedQuery {
        $productDiscontinuedConditionsTransfer = $productDiscontinuedCriteriaTransfer->getProductDiscontinuedConditions();
        if ($productDiscontinuedConditionsTransfer === null) {
            return $productDiscontinuedQuery;
        }

        if ($productDiscontinuedConditionsTransfer->getProductDiscontinuedIds()) {
            $productDiscontinuedQuery
                ->filterByIdProductDiscontinued_In($productDiscontinuedConditionsTransfer->getProductDiscontinuedIds());
        }

        if ($productDiscontinuedConditionsTransfer->getProductIds()) {
            $productDiscontinuedQuery
                ->filterByFkProduct_In($productDiscontinuedConditionsTransfer->getProductIds());
        }

        if ($productDiscontinuedConditionsTransfer->getSkus()) {
            $productDiscontinuedQuery
                ->useProductQuery()
                    ->filterBySku_In($productDiscontinuedConditionsTransfer->getSkus())
                ->endUse();
        }

        return $productDiscontinuedQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery $productDiscontinuedQuery
     *
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery
     */
    protected function applyProductDiscontinuedPagination(
        PaginationTransfer $paginationTransfer,
        SpyProductDiscontinuedQuery $productDiscontinuedQuery
    ): SpyProductDiscontinuedQuery {
        $paginationTransfer->setNbResults($productDiscontinuedQuery->count());

        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $productDiscontinuedQuery
                ->limit($paginationTransfer->getLimitOrFail())
                ->offset($paginationTransfer->getOffsetOrFail());
        }

        return $productDiscontinuedQuery;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued> $productDiscontinuedEntities
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued>
     */
    protected function expandWithProductDiscontinuedNotes(ObjectCollection $productDiscontinuedEntities): ObjectCollection
    {
        $productDiscontinuedEntitiesIndexedByProductDiscontinuedIds =
            $this->indexProductDiscontinuedEntitiesByProductDiscontinuedIds($productDiscontinuedEntities);

        $productDiscontinuedNoteEntities = $this->getFactory()
            ->createProductDiscontinuedNoteQuery()
            ->filterByFkProductDiscontinued_In(
                array_keys($productDiscontinuedEntitiesIndexedByProductDiscontinuedIds),
            )
            ->find();

        foreach ($productDiscontinuedNoteEntities as $productDiscontinuedNoteEntity) {
            $productDiscontinuedId = $productDiscontinuedNoteEntity->getFkProductDiscontinued();
            if (!isset($productDiscontinuedEntitiesIndexedByProductDiscontinuedIds[$productDiscontinuedId])) {
                continue;
            }

            $productDiscontinuedEntitiesIndexedByProductDiscontinuedIds[$productDiscontinuedId]
                ->addSpyProductDiscontinuedNote($productDiscontinuedNoteEntity);
        }

        return $productDiscontinuedEntities;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued> $productDiscontinuedEntities
     *
     * @return array<int, \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued>
     */
    protected function indexProductDiscontinuedEntitiesByProductDiscontinuedIds(
        ObjectCollection $productDiscontinuedEntities
    ): array {
        $productDiscontinuedEntitiesIndexedByProductDiscontinuedIds = [];
        foreach ($productDiscontinuedEntities as $productDiscontinuedEntity) {
            $productDiscontinuedEntitiesIndexedByProductDiscontinuedIds[$productDiscontinuedEntity->getIdProductDiscontinued()] = $productDiscontinuedEntity;
        }

        return $productDiscontinuedEntitiesIndexedByProductDiscontinuedIds;
    }
}
