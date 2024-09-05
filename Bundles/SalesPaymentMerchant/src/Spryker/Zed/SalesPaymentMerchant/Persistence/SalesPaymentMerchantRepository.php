<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCriteriaTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCriteriaTransfer;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutQuery;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversalQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantPersistenceFactory getFactory()
 */
class SalesPaymentMerchantRepository extends AbstractRepository implements SalesPaymentMerchantRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCriteriaTransfer $salesPaymentMerchantPayoutCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutCollection(
        SalesPaymentMerchantPayoutCriteriaTransfer $salesPaymentMerchantPayoutCriteriaTransfer
    ): SalesPaymentMerchantPayoutCollectionTransfer {
        $salesPaymentMerchantPayoutCollectionTransfer = new SalesPaymentMerchantPayoutCollectionTransfer();
        $salesPaymentMerchantPayoutQuery = $this->getFactory()
            ->getSalesPaymentMerchantPayoutQuery();

        $salesPaymentMerchantPayoutQuery = $this->applySalesPaymentMerchantPayoutFilters(
            $salesPaymentMerchantPayoutQuery,
            $salesPaymentMerchantPayoutCriteriaTransfer,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $salesPaymentMerchantPayoutCriteriaTransfer->getSortCollection();
        $salesPaymentMerchantPayoutQuery = $this->applySorting($salesPaymentMerchantPayoutQuery, $sortTransfers);

        $paginationTransfer = $salesPaymentMerchantPayoutCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $salesPaymentMerchantPayoutQuery = $this->applyPagination(
                $salesPaymentMerchantPayoutQuery,
                $paginationTransfer,
            );
            $salesPaymentMerchantPayoutCollectionTransfer->setPagination($paginationTransfer);
        }

        $salesPaymentMerchantPayoutEntities = $salesPaymentMerchantPayoutQuery->find();

        if ($salesPaymentMerchantPayoutEntities->count() === 0) {
            return $salesPaymentMerchantPayoutCollectionTransfer;
        }

        return $this->getFactory()
            ->createSalesPaymentMerchantPayoutMapper()
            ->mapSalesPaymentMerchantPayoutEntitiesToSalesPaymentMerchantPayoutCollectionTransfer(
                $salesPaymentMerchantPayoutEntities,
                $salesPaymentMerchantPayoutCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutQuery $salesPaymentMerchantPayoutQuery
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCriteriaTransfer $salesPaymentMerchantPayoutCriteriaTransfer
     *
     * @return \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutQuery
     */
    protected function applySalesPaymentMerchantPayoutFilters(
        SpySalesPaymentMerchantPayoutQuery $salesPaymentMerchantPayoutQuery,
        SalesPaymentMerchantPayoutCriteriaTransfer $salesPaymentMerchantPayoutCriteriaTransfer
    ): SpySalesPaymentMerchantPayoutQuery {
        $salesPaymentMerchantPayoutConditionsTransfer = $salesPaymentMerchantPayoutCriteriaTransfer->getSalesPaymentMerchantPayoutConditions();
        if (!$salesPaymentMerchantPayoutConditionsTransfer) {
            return $salesPaymentMerchantPayoutQuery;
        }

        if ($salesPaymentMerchantPayoutConditionsTransfer->getMerchantReferences() !== []) {
            $salesPaymentMerchantPayoutQuery->filterByMerchantReference_In(
                $salesPaymentMerchantPayoutConditionsTransfer->getMerchantReferences(),
            );
        }

        if ($salesPaymentMerchantPayoutConditionsTransfer->getOrderReferences() !== []) {
            $salesPaymentMerchantPayoutQuery->filterByOrderReference_In(
                $salesPaymentMerchantPayoutConditionsTransfer->getOrderReferences(),
            );
        }

        if ($salesPaymentMerchantPayoutConditionsTransfer->getIsSuccessful() !== null) {
            $salesPaymentMerchantPayoutQuery->filterByIsSuccessful(
                $salesPaymentMerchantPayoutConditionsTransfer->getIsSuccessful(),
            );
        }

        if ($salesPaymentMerchantPayoutConditionsTransfer->getItemReferences() !== []) {
            $salesPaymentMerchantPayoutQuery->filterByItemReferences_In(
                $salesPaymentMerchantPayoutConditionsTransfer->getItemReferences(),
            );
        }

        // Only fetch entities that have a transferId and are not failed.
        $salesPaymentMerchantPayoutQuery->filterByTransferId(null, Criteria::ISNOTNULL);

        return $salesPaymentMerchantPayoutQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCriteriaTransfer $salesPaymentMerchantPayoutReversalCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutReversalCollection(
        SalesPaymentMerchantPayoutReversalCriteriaTransfer $salesPaymentMerchantPayoutReversalCriteriaTransfer
    ): SalesPaymentMerchantPayoutReversalCollectionTransfer {
        $salesPaymentMerchantPayoutReversalCollectionTransfer = new SalesPaymentMerchantPayoutReversalCollectionTransfer();
        $salesPaymentMerchantPayoutReversalQuery = $this->getFactory()
            ->getSalesPaymentMerchantPayoutReversalQuery();

        $salesPaymentMerchantPayoutReversalQuery = $this->applySalesPaymentMerchantPayoutReversalFilters(
            $salesPaymentMerchantPayoutReversalQuery,
            $salesPaymentMerchantPayoutReversalCriteriaTransfer,
        );

        $sortTransfers = $salesPaymentMerchantPayoutReversalCriteriaTransfer->getSortCollection();
        $salesPaymentMerchantPayoutReversalQuery = $this->applySorting($salesPaymentMerchantPayoutReversalQuery, $sortTransfers);

        $paginationTransfer = $salesPaymentMerchantPayoutReversalCriteriaTransfer->getPagination();
        if ($paginationTransfer) {
            $salesPaymentMerchantPayoutReversalQuery = $this->applyPagination(
                $salesPaymentMerchantPayoutReversalQuery,
                $paginationTransfer,
            );
            $salesPaymentMerchantPayoutReversalCollectionTransfer->setPagination($paginationTransfer);
        }

        $salesPaymentMerchantPayoutReversalEntities = $salesPaymentMerchantPayoutReversalQuery->find();
        if ($salesPaymentMerchantPayoutReversalEntities->count() === 0) {
            return $salesPaymentMerchantPayoutReversalCollectionTransfer;
        }

        return $this->getFactory()
            ->createSalesPaymentMerchantPayoutReversalMapper()
            ->mapSalesPaymentMerchantPayoutReversalEntitiesToSalesPaymentMerchantPayoutReversalCollectionTransfer(
                $salesPaymentMerchantPayoutReversalEntities,
                $salesPaymentMerchantPayoutReversalCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversalQuery $salesPaymentMerchantPayoutReversalQuery
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCriteriaTransfer $salesPaymentMerchantPayoutReversalCriteriaTransfer
     *
     * @return \Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversalQuery
     */
    protected function applySalesPaymentMerchantPayoutReversalFilters(
        SpySalesPaymentMerchantPayoutReversalQuery $salesPaymentMerchantPayoutReversalQuery,
        SalesPaymentMerchantPayoutReversalCriteriaTransfer $salesPaymentMerchantPayoutReversalCriteriaTransfer
    ): SpySalesPaymentMerchantPayoutReversalQuery {
        $salesPaymentMerchantPayoutReversalConditionsTransfer = $salesPaymentMerchantPayoutReversalCriteriaTransfer->getSalesPaymentMerchantPayoutReversalConditions();
        if (!$salesPaymentMerchantPayoutReversalConditionsTransfer) {
            return $salesPaymentMerchantPayoutReversalQuery;
        }

        if ($salesPaymentMerchantPayoutReversalConditionsTransfer->getMerchantReferences() !== []) {
            $salesPaymentMerchantPayoutReversalQuery->filterByMerchantReference_In(
                $salesPaymentMerchantPayoutReversalConditionsTransfer->getMerchantReferences(),
            );
        }

        if ($salesPaymentMerchantPayoutReversalConditionsTransfer->getOrderReferences() !== []) {
            $salesPaymentMerchantPayoutReversalQuery->filterByOrderReference_In(
                $salesPaymentMerchantPayoutReversalConditionsTransfer->getOrderReferences(),
            );
        }

        if ($salesPaymentMerchantPayoutReversalConditionsTransfer->getIsSuccessful() !== null) {
            $salesPaymentMerchantPayoutReversalQuery->filterByIsSuccessful(
                $salesPaymentMerchantPayoutReversalConditionsTransfer->getIsSuccessful(),
            );
        }

        return $salesPaymentMerchantPayoutReversalQuery;
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
            $modelCriteria->orderBy($sortTransfer->getFieldOrFail(), $sortTransfer->getIsAscending() ? Criteria::ASC : Criteria::DESC);
        }

        return $modelCriteria;
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
}
