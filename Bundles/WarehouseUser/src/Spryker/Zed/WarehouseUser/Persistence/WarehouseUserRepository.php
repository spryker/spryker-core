<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserPersistenceFactory getFactory()
 */
class WarehouseUserRepository extends AbstractRepository implements WarehouseUserRepositoryInterface
{
    /**
     * @module Stock
     *
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer
     */
    public function getWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCriteriaTransfer $warehouseUserAssignmentCriteriaTransfer
    ): WarehouseUserAssignmentCollectionTransfer {
        $warehouseUserAssignmentCollectionTransfer = new WarehouseUserAssignmentCollectionTransfer();

        $warehouseUserAssignmentQuery = $this->getFactory()
            ->createWarehouseUserAssignmentQuery()
            ->joinWithSpyStock();
        $warehouseUserAssignmentQuery = $this->applyWarehouseUserAssignmentFilters(
            $warehouseUserAssignmentQuery,
            $warehouseUserAssignmentCriteriaTransfer->getWarehouseUserAssignmentConditions(),
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransferCollection */
        $sortTransferCollection = $warehouseUserAssignmentCriteriaTransfer->getSortCollection();

        $warehouseUserAssignmentQuery = $this->applyWarehouseUserAssignmentSorting(
            $warehouseUserAssignmentQuery,
            $sortTransferCollection,
        );
        $paginationTransfer = $warehouseUserAssignmentCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $warehouseUserAssignmentQuery = $this->applyWarehouseUserAssignmentPagination($warehouseUserAssignmentQuery, $paginationTransfer);
            $warehouseUserAssignmentCollectionTransfer->setPagination($paginationTransfer);
        }

        $warehouseUserAssignmentEntityCollection = $warehouseUserAssignmentQuery->find();
        if ($warehouseUserAssignmentEntityCollection->count() === 0) {
            return $warehouseUserAssignmentCollectionTransfer;
        }

        return $this->getFactory()
            ->createWarehouseUserMapper()
            ->mapWarehouseUserAssignmentEntityCollectionToWarehouseUserAssignmentCollectionTransfer(
                $warehouseUserAssignmentEntityCollection,
                $warehouseUserAssignmentCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment> $warehouseUserAssignmentQuery
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentConditionsTransfer|null $warehouseUserAssignmentConditionsTransfer
     *
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment>
     */
    protected function applyWarehouseUserAssignmentFilters(
        SpyWarehouseUserAssignmentQuery $warehouseUserAssignmentQuery,
        ?WarehouseUserAssignmentConditionsTransfer $warehouseUserAssignmentConditionsTransfer
    ): SpyWarehouseUserAssignmentQuery {
        if ($warehouseUserAssignmentConditionsTransfer === null) {
            return $warehouseUserAssignmentQuery;
        }

        if ($warehouseUserAssignmentConditionsTransfer->getWarehouseUserAssignmentIds() !== []) {
            $warehouseUserAssignmentQuery->filterByIdWarehouseUserAssignment_In(
                $warehouseUserAssignmentConditionsTransfer->getWarehouseUserAssignmentIds(),
            );
        }

        if ($warehouseUserAssignmentConditionsTransfer->getUuids() !== []) {
            $warehouseUserAssignmentQuery->filterByUuid_In($warehouseUserAssignmentConditionsTransfer->getUuids());
        }

        if ($warehouseUserAssignmentConditionsTransfer->getUserUuids() !== []) {
            $warehouseUserAssignmentQuery->filterByUserUuid_In($warehouseUserAssignmentConditionsTransfer->getUserUuids());
        }

        if ($warehouseUserAssignmentConditionsTransfer->getWarehouseUuids() !== []) {
            $warehouseUserAssignmentQuery
                ->useSpyStockQuery()
                    ->filterByUuid_In($warehouseUserAssignmentConditionsTransfer->getWarehouseUuids())
                ->endUse();
        }

        if ($warehouseUserAssignmentConditionsTransfer->getIsActive() !== null) {
            $warehouseUserAssignmentQuery->filterByIsActive($warehouseUserAssignmentConditionsTransfer->getIsActiveOrFail());
        }

        return $warehouseUserAssignmentQuery;
    }

    /**
     * @param \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment> $warehouseUserAssignmentQuery
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment>
     */
    protected function applyWarehouseUserAssignmentSorting(
        SpyWarehouseUserAssignmentQuery $warehouseUserAssignmentQuery,
        ArrayObject $sortTransfers
    ): SpyWarehouseUserAssignmentQuery {
        foreach ($sortTransfers as $sortTransfer) {
            $warehouseUserAssignmentQuery->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $warehouseUserAssignmentQuery;
    }

    /**
     * @param \Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment> $warehouseUserAssignmentQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria<\Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignment>
     */
    protected function applyWarehouseUserAssignmentPagination(
        SpyWarehouseUserAssignmentQuery $warehouseUserAssignmentQuery,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null || $paginationTransfer->getLimit() !== null) {
            $warehouseUserAssignmentQuery
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());

            return $warehouseUserAssignmentQuery;
        }

        $paginationModel = $warehouseUserAssignmentQuery->paginate(
            $paginationTransfer->getPageOrFail(),
            $paginationTransfer->getMaxPerPageOrFail(),
        );
        $paginationTransfer
            ->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }
}
