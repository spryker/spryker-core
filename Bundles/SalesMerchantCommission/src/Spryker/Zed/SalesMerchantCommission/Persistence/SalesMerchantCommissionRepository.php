<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer;
use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionPersistenceFactory getFactory()
 */
class SalesMerchantCommissionRepository extends AbstractRepository implements SalesMerchantCommissionRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function getSalesMerchantCommissionCollection(
        SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
    ): SalesMerchantCommissionCollectionTransfer {
        $salesMerchantCommissionQuery = $this->getFactory()->getSalesMerchantCommissionQuery();
        $salesMerchantCommissionQuery = $this->applySalesMerchantCommissionFilters(
            $salesMerchantCommissionQuery,
            $salesMerchantCommissionCriteriaTransfer,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SortTransfer> $sortTransfers */
        $sortTransfers = $salesMerchantCommissionCriteriaTransfer->getSortCollection();
        $salesMerchantCommissionQuery = $this->applySorting($salesMerchantCommissionQuery, $sortTransfers);
        $salesMerchantCommissionCollectionTransfer = new SalesMerchantCommissionCollectionTransfer();
        $paginationTransfer = $salesMerchantCommissionCriteriaTransfer->getPagination();

        if ($paginationTransfer) {
            $salesMerchantCommissionQuery = $this->applyPagination($salesMerchantCommissionQuery, $paginationTransfer);
            $salesMerchantCommissionCollectionTransfer->setPagination($paginationTransfer);
        }

        /** @phpstan-var \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery $salesMerchantCommissionQuery */
        $salesMerchantCommissionEntities = $salesMerchantCommissionQuery->groupByIdSalesMerchantCommission()->find();

        return $this->getFactory()
            ->createSalesMerchantCommissionMapper()
            ->mapSalesMerchantCommissionEntityCollectionToSalesMerchantCommissionCollectionTransfer(
                $salesMerchantCommissionEntities,
                $salesMerchantCommissionCollectionTransfer,
            );
    }

    /**
     * @param \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery $salesMerchantCommissionQuery
     * @param \Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
     *
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery
     */
    protected function applySalesMerchantCommissionFilters(
        SpySalesMerchantCommissionQuery $salesMerchantCommissionQuery,
        SalesMerchantCommissionCriteriaTransfer $salesMerchantCommissionCriteriaTransfer
    ): SpySalesMerchantCommissionQuery {
        $salesMerchantCommissionConditionsTransfer = $salesMerchantCommissionCriteriaTransfer->getSalesMerchantCommissionConditions();

        if (!$salesMerchantCommissionConditionsTransfer) {
            return $salesMerchantCommissionQuery;
        }

        if ($salesMerchantCommissionConditionsTransfer->getSalesOrderItemIds()) {
            $salesMerchantCommissionQuery->filterByFkSalesOrderItem_In($salesMerchantCommissionConditionsTransfer->getSalesOrderItemIds());
        }

        if ($salesMerchantCommissionConditionsTransfer->getSalesOrderIds()) {
            $salesMerchantCommissionQuery->filterByFkSalesOrder_In($salesMerchantCommissionConditionsTransfer->getSalesOrderIds());
        }

        return $salesMerchantCommissionQuery;
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
