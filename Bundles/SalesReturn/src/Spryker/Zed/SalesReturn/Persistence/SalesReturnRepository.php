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
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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
    public function findSalesOrderItemByIdSalesOrder(int $idSalesOrderItem): ?ItemTransfer
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
     * @return \Generated\Shared\Transfer\ReturnReasonTransfer[]
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): array
    {
        $returnReasonQuery = $this->getFactory()->getSalesReturnReasonPropelQuery();

        $returnReasonQuery = $this->buildQueryFromCriteria(
            $returnReasonQuery,
            $returnReasonFilterTransfer->getFilter()
        );

        $returnReasonQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        return $this->getFactory()
            ->createReturnReasonMapper()
            ->mapReturnReasonEntityCollectionToReturnReasonTransfers($returnReasonQuery->find());
    }

    /**
     * @param string $customerReference
     *
     * @return int
     */
    public function countCustomerReturns(string $customerReference): int
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
        $salesReturnQuery = $this->getFactory()->getSalesReturnPropelQuery();

        $salesReturnQuery = $this->setSalesReturnFilters($salesReturnQuery, $returnFilterTransfer);

        return $this->getFactory()
            ->createReturnMapper()
            ->mapReturnEntityCollectionToReturnCollection($salesReturnQuery->find())
            ->setPagination($returnFilterTransfer->getPagination());
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

        if ($returnFilterTransfer->getPagination()) {
            $salesReturnQuery = $this->preparePagination($salesReturnQuery, $returnFilterTransfer->getPagination());
        }

        return $salesReturnQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    protected function preparePagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): SpySalesReturnQuery
    {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }
}
