<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\Persistence\SalesOrderAmendmentPersistenceFactory getFactory()
 */
class SalesOrderAmendmentRepository extends AbstractRepository implements SalesOrderAmendmentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentCollectionTransfer
     */
    public function getSalesOrderAmendmentCollection(
        SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
    ): SalesOrderAmendmentCollectionTransfer {
        $salesOrderAmendmentCollectionTransfer = new SalesOrderAmendmentCollectionTransfer();

        $salesOrderAmendmentQuery = $this->getFactory()->getSalesOrderAmendmentQuery();
        $salesOrderAmendmentQuery = $this->applySalesOrderAmendmentFilters($salesOrderAmendmentQuery, $salesOrderAmendmentCriteriaTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $salesOrderAmendmentCriteriaTransfer->getSortCollection();
        $salesOrderAmendmentQuery = $this->applySorting($salesOrderAmendmentQuery, $sortTransfers);

        $paginationTransfer = $salesOrderAmendmentCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $salesOrderAmendmentQuery = $this->applyPagination($salesOrderAmendmentQuery, $paginationTransfer);
            $salesOrderAmendmentCollectionTransfer->setPagination($paginationTransfer);
        }

        $salesOrderAmendmentEntities = $salesOrderAmendmentQuery->find();
        if ($salesOrderAmendmentEntities->count() === 0) {
            return $salesOrderAmendmentCollectionTransfer;
        }

        return $this->getFactory()
            ->createSalesOrderAmendmentMapper()
            ->mapSalesOrderAmendmentEntitiesToSalesOrderAmendmentCollectionTransfer(
                $salesOrderAmendmentEntities,
                $salesOrderAmendmentCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer|null
     */
    public function findSalesOrderAmendmentByDeleteCriteria(
        SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
    ): ?SalesOrderAmendmentTransfer {
        $salesOrderAmendmentQuery = $this->getFactory()->getSalesOrderAmendmentQuery();
        $salesOrderAmendmentQuery = $this->applySalesOrderAmendmentDeleteFilters(
            $salesOrderAmendmentDeleteCriteriaTransfer,
            $salesOrderAmendmentQuery,
        );

        $salesOrderAmendmentEntity = $salesOrderAmendmentQuery->findOne();
        if ($salesOrderAmendmentEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createSalesOrderAmendmentMapper()
            ->mapSalesOrderAmendmentEntityToSalesOrderAmendmentTransfer(
                $salesOrderAmendmentEntity,
                new SalesOrderAmendmentTransfer(),
            );
    }

    /**
     * @param \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery $salesOrderAmendmentQuery
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
     *
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery
     */
    protected function applySalesOrderAmendmentFilters(
        SpySalesOrderAmendmentQuery $salesOrderAmendmentQuery,
        SalesOrderAmendmentCriteriaTransfer $salesOrderAmendmentCriteriaTransfer
    ): SpySalesOrderAmendmentQuery {
        $salesOrderAmendmentConditionsTransfer = $salesOrderAmendmentCriteriaTransfer->getSalesOrderAmendmentConditions();
        if (!$salesOrderAmendmentConditionsTransfer) {
            return $salesOrderAmendmentQuery;
        }

        if ($salesOrderAmendmentConditionsTransfer->getSalesOrderAmendmentIds() !== []) {
            $salesOrderAmendmentQuery->filterByIdSalesOrderAmendment_In($salesOrderAmendmentConditionsTransfer->getSalesOrderAmendmentIds());
        }

        if ($salesOrderAmendmentConditionsTransfer->getUuids() !== []) {
            $salesOrderAmendmentQuery->filterByUuid_In($salesOrderAmendmentConditionsTransfer->getUuids());
        }

        if ($salesOrderAmendmentConditionsTransfer->getOriginalOrderReferences() !== []) {
            $salesOrderAmendmentQuery->filterByOriginalOrderReference_In($salesOrderAmendmentConditionsTransfer->getOriginalOrderReferences());
        }

        return $salesOrderAmendmentQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer
     * @param \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery $salesOrderAmendmentQuery
     *
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery
     */
    protected function applySalesOrderAmendmentDeleteFilters(
        SalesOrderAmendmentDeleteCriteriaTransfer $salesOrderAmendmentDeleteCriteriaTransfer,
        SpySalesOrderAmendmentQuery $salesOrderAmendmentQuery
    ): SpySalesOrderAmendmentQuery {
        if ($salesOrderAmendmentDeleteCriteriaTransfer->getIdSalesOrderAmendment()) {
            $salesOrderAmendmentQuery->filterByIdSalesOrderAmendment($salesOrderAmendmentDeleteCriteriaTransfer->getIdSalesOrderAmendmentOrFail());
        }

        if ($salesOrderAmendmentDeleteCriteriaTransfer->getUuid()) {
            $salesOrderAmendmentQuery->filterByUuid($salesOrderAmendmentDeleteCriteriaTransfer->getUuidOrFail());
        }

        return $salesOrderAmendmentQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyPagination(
        ModelCriteria $modelCriteria,
        PaginationTransfer $paginationTransfer
    ): ModelCriteria {
        if ($paginationTransfer->getOffset() !== null && $paginationTransfer->getLimit() !== null) {
            $paginationTransfer->setNbResults($modelCriteria->count());

            return $modelCriteria
                ->offset($paginationTransfer->getOffsetOrFail())
                ->setLimit($paginationTransfer->getLimitOrFail());
        }

        if ($paginationTransfer->getPage() !== null && $paginationTransfer->getMaxPerPage()) {
            $propelModelPager = $modelCriteria->paginate(
                $paginationTransfer->getPageOrFail(),
                $paginationTransfer->getMaxPerPageOrFail(),
            );

            $paginationTransfer->setNbResults($propelModelPager->getNbResults())
                ->setFirstIndex($propelModelPager->getFirstIndex())
                ->setLastIndex($propelModelPager->getLastIndex())
                ->setFirstPage($propelModelPager->getFirstPage())
                ->setLastPage($propelModelPager->getLastPage())
                ->setNextPage($propelModelPager->getNextPage())
                ->setPreviousPage($propelModelPager->getPreviousPage());

            return $propelModelPager->getQuery();
        }

        return $modelCriteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $modelCriteria
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applySorting(
        ModelCriteria $modelCriteria,
        ArrayObject $sortTransfers
    ): ModelCriteria {
        foreach ($sortTransfers as $sortTransfer) {
            $modelCriteria->orderBy(
                $sortTransfer->getFieldOrFail(),
                $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC,
            );
        }

        return $modelCriteria;
    }
}
