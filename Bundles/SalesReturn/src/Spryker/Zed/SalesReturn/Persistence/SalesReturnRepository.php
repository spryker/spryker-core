<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Persistence;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnItemFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesReturn\Persistence\SalesReturnPersistenceFactory getFactory()
 */
class SalesReturnRepository extends AbstractRepository implements SalesReturnRepositoryInterface
{
    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findSalesOrderItemById(int $idSalesOrderItem): ?ItemTransfer
    {
        $salesOrderItemEntity = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if (!$salesOrderItemEntity) {
            return null;
        }

        return (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasonCollectionByFilter(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        $returnReasonQuery = $this->getFactory()->getSalesReturnReasonPropelQuery();

        $paginationTransfer = (new PaginationTransfer())->setNbResults($returnReasonQuery->count());

        $returnReasonQuery = $this->setSalesReturnReasonFilters($returnReasonQuery, $returnReasonFilterTransfer);

        $returnReasonQuery = $this->buildQueryFromCriteria(
            $returnReasonQuery,
            $returnReasonFilterTransfer->getFilter()
        );

        $returnReasonQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $this->getFactory()
            ->createReturnReasonMapper()
            ->mapReturnReasonEntityCollectionToReturnReasonCollection($returnReasonQuery->find())
            ->setPagination($paginationTransfer);
    }

    /**
     * @param string|null $customerReference
     *
     * @return int
     */
    public function countCustomerReturns(?string $customerReference = null): int
    {
        return $this->getFactory()
            ->getSalesReturnPropelQuery()
            ->filterByCustomerReference($customerReference)
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturnCollectionByFilter(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        $filterTransfer = $returnFilterTransfer->getFilter();

        $salesReturnQuery = $this->getFactory()
            ->getSalesReturnPropelQuery()
            ->orderBy($filterTransfer->getOrderBy(), $filterTransfer->getOrderDirection());

        $salesReturnQuery = $this->setSalesReturnFilters($salesReturnQuery, $returnFilterTransfer);

        if ($filterTransfer->getOffset() === null && $filterTransfer->getLimit() == null) {
            $paginationTransfer = (new PaginationTransfer())->setNbResults($salesReturnQuery->count());

            return $this->getFactory()
                ->createReturnMapper()
                ->mapReturnEntityCollectionToReturnCollectionTransfer($salesReturnQuery->find())
                ->setPagination($paginationTransfer);
        }

        $page = 1;

        if ($filterTransfer->getOffset() && $filterTransfer->getLimit()) {
            $page = $filterTransfer->getOffset() / $filterTransfer->getLimit() + 1;
        }

        $propelModelPager = $salesReturnQuery->paginate($page, $filterTransfer->getLimit());
        $paginationTransfer = $this->getPagination($propelModelPager);

        return $this->getFactory()
            ->createReturnMapper()
            ->mapReturnEntityCollectionToReturnCollectionTransfer($propelModelPager->getQuery()->find())
            ->setPagination($paginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemFilterTransfer $returnItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnItemTransfer[]
     */
    public function getReturnItemsByFilter(ReturnItemFilterTransfer $returnItemFilterTransfer): array
    {
        $salesReturnItemQuery = $this->getFactory()->getSalesReturnItemPropelQuery();

        if ($returnItemFilterTransfer->getReturnIds()) {
            $salesReturnItemQuery->filterByFkSalesReturn_In($returnItemFilterTransfer->getReturnIds());
        }

        return $this->getFactory()
            ->createReturnMapper()
            ->mapReturnItemEntityCollectionToReturnTransfers($salesReturnItemQuery->find());
    }

    /**
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery $salesReturnQuery
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    protected function setSalesReturnFilters(
        SpySalesReturnQuery $salesReturnQuery,
        ReturnFilterTransfer $returnFilterTransfer
    ): SpySalesReturnQuery {
        if ($returnFilterTransfer->getReturnReference()) {
            $salesReturnQuery->filterByReturnReference($returnFilterTransfer->getReturnReference());
        }

        if ($returnFilterTransfer->getCustomerReference()) {
            $salesReturnQuery->filterByCustomerReference($returnFilterTransfer->getCustomerReference());
        }

        if ($returnFilterTransfer->getReturnIds()) {
            $salesReturnQuery->filterByIdSalesReturn_In($returnFilterTransfer->getReturnIds());
        }

        return $salesReturnQuery;
    }

    /**
     * @param \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery $salesReturnReasonQuery
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnReasonQuery
     */
    protected function setSalesReturnReasonFilters(
        SpySalesReturnReasonQuery $salesReturnReasonQuery,
        ReturnReasonFilterTransfer $returnReasonFilterTransfer
    ): SpySalesReturnReasonQuery {
        if ($returnReasonFilterTransfer->getReturnReasonIds()) {
            $salesReturnReasonQuery->filterByIdSalesReturnReason_In($returnReasonFilterTransfer->getReturnReasonIds());
        }

        return $salesReturnReasonQuery;
    }

    /**
     * @param \Propel\Runtime\Util\PropelModelPager $propelModelPager
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPagination(PropelModelPager $propelModelPager): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setNbResults($propelModelPager->getNbResults())
            ->setPage($propelModelPager->getPage())
            ->setMaxPerPage($propelModelPager->getMaxPerPage())
            ->setFirstIndex($propelModelPager->getFirstIndex())
            ->setLastIndex($propelModelPager->getLastIndex())
            ->setFirstPage($propelModelPager->getFirstPage())
            ->setLastPage($propelModelPager->getLastPage())
            ->setNextPage($propelModelPager->getNextPage())
            ->setPreviousPage($propelModelPager->getPreviousPage());
    }
}
