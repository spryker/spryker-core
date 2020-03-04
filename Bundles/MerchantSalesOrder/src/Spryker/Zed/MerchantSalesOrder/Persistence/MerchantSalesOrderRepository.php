<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderPersistenceFactory getFactory()
 */
class MerchantSalesOrderRepository extends AbstractRepository implements MerchantSalesOrderRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderCollection(
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantSalesOrderQuery = $this->getFactory()->createMerchantSalesOrderQuery();
        $merchantSalesOrderQuery = $this->addMerchantSalesOrderTotalsDataToMerchantSalesOrderQuery(
            $merchantSalesOrderQuery
        );
        $merchantSalesOrderQuery = $this->applyFilters($merchantSalesOrderQuery, $merchantOrderCriteriaTransfer);
        /** @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery */
        $merchantSalesOrderQuery = $this->buildQueryFromCriteria(
            $merchantSalesOrderQuery,
            $merchantOrderCriteriaTransfer->getFilter()
        );
        $merchantSalesOrderQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        $merchantSalesOrderEntityCollection = $this
            ->applyPagination($merchantSalesOrderQuery, $merchantOrderCriteriaTransfer->getPagination())
            ->find();

        if ($merchantSalesOrderEntityCollection->count() === 0) {
            return new MerchantOrderCollectionTransfer();
        }

        /** @var \Generated\Shared\Transfer\MerchantOrderTransfer[] $merchantOrderTransfers */
        $merchantOrderTransfers = [];
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        foreach ($merchantSalesOrderEntityCollection as $merchantSalesOrderEntity) {
            $merchantOrderTransfers[$merchantSalesOrderEntity->getIdMerchantSalesOrder()] = $merchantSalesOrderMapper
                ->mapMerchantSalesOrderEntityToMerchantOrderTransfer(
                    $merchantSalesOrderEntity,
                    new MerchantOrderTransfer()
                );
        }

        if ($merchantOrderCriteriaTransfer->getWithItems()) {
            $merchantOrderTransfers = $this->addMerchantOrderItemsToMerchantOrders($merchantOrderTransfers);
        }

        return (new MerchantOrderCollectionTransfer())->setMerchantOrders(
            new ArrayObject(array_values($merchantOrderTransfers))
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer[] $merchantOrderTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer[]
     */
    protected function addMerchantOrderItemsToMerchantOrders(array $merchantOrderTransfers): array
    {
        $merchantSalesOrderItemEntityCollection = $this->getMerchantSalesOrderItemEntityCollectionByMerchantOrderIds(
            array_keys($merchantOrderTransfers)
        );

        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        foreach ($merchantSalesOrderItemEntityCollection as $merchantSalesOrderItemEntity) {
            /** @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity */
            $merchantOrderTransfers[$merchantSalesOrderItemEntity->getFkMerchantSalesOrder()]->addMerchantOrderItem(
                $merchantSalesOrderMapper->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
                    $merchantSalesOrderItemEntity,
                    new MerchantOrderItemTransfer()
                )
            );
        }

        return $merchantOrderTransfers;
    }

    /**
     * @param int[] $merchantOrderIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    protected function getMerchantSalesOrderItemEntityCollectionByMerchantOrderIds(
        array $merchantOrderIds
    ): ObjectCollection {
        $merchantSalesOrderItemQuery = $this->getFactory()->createMerchantSalesOrderItemQuery();

        return $merchantSalesOrderItemQuery
            ->filterByFkMerchantSalesOrder_In($merchantOrderIds)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): ?MerchantOrderTransfer {
        $merchantSalesOrderQuery = $this->getFactory()->createMerchantSalesOrderQuery();
        $merchantSalesOrderQuery = $this->addMerchantSalesOrderTotalsDataToMerchantSalesOrderQuery(
            $merchantSalesOrderQuery
        );
        $merchantSalesOrderEntity = $this
            ->applyFilters($merchantSalesOrderQuery, $merchantOrderCriteriaTransfer)
            ->findOne();

        if (!$merchantSalesOrderEntity) {
            return null;
        }

        if ($merchantOrderCriteriaTransfer->getWithItems()) {
            $merchantSalesOrderEntity->getMerchantSalesOrderItems();
        }

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderEntityToMerchantOrderTransfer($merchantSalesOrderEntity, new MerchantOrderTransfer());
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addMerchantSalesOrderTotalsDataToMerchantSalesOrderQuery(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
    ): SpyMerchantSalesOrderQuery {
        $merchantSalesOrderQuery->useMerchantSalesOrderTotalQuery()
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_REFUND_TOTAL, TotalsTransfer::REFUND_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL, TotalsTransfer::GRAND_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_TAX_TOTAL, TotalsTransfer::TAX_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_ORDER_EXPENSE_TOTAL, TotalsTransfer::EXPENSE_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_SUBTOTAL, TotalsTransfer::SUBTOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_DISCOUNT_TOTAL, TotalsTransfer::DISCOUNT_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_CANCELED_TOTAL, TotalsTransfer::CANCELED_TOTAL)
        ->endUse();

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyFilters(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        if ($merchantOrderCriteriaTransfer->getIdMerchantOrder() !== null) {
            $merchantSalesOrderQuery->filterByIdMerchantSalesOrder(
                $merchantOrderCriteriaTransfer->getIdMerchantOrder()
            );
        }

        if ($merchantOrderCriteriaTransfer->getMerchantOrderReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantSalesOrderReference(
                $merchantOrderCriteriaTransfer->getMerchantOrderReference()
            );
        }

        if ($merchantOrderCriteriaTransfer->getMerchantReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantReference(
                $merchantOrderCriteriaTransfer->getMerchantReference()
            );
        }

        if ($merchantOrderCriteriaTransfer->getIdOrder() !== null) {
            $merchantSalesOrderQuery->filterByFkSalesOrder(
                $merchantOrderCriteriaTransfer->getIdOrder()
            );
        }

        if ($merchantOrderCriteriaTransfer->getIdMerchant() !== null) {
            $merchantSalesOrderQuery->addJoin(
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                Criteria::INNER_JOIN
            );
            $merchantSalesOrderQuery->addAnd(
                SpyMerchantTableMap::COL_ID_MERCHANT,
                $merchantOrderCriteriaTransfer->getIdMerchant()
            );
        }

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyPagination(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        ?PaginationTransfer $paginationTransfer = null
    ): SpyMerchantSalesOrderQuery {
        if (!$paginationTransfer) {
            return $merchantSalesOrderQuery;
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPage();
        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();
        $paginationModel = $merchantSalesOrderQuery->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }

    /**
     * @param int $idMerchantOrderItem
     *
     * @return bool
     */
    public function existsMerchantOrderItem(int $idMerchantOrderItem): bool
    {
        return $this->getFactory()
            ->createMerchantSalesOrderItemQuery()
            ->filterByIdMerchantSalesOrderItem($idMerchantOrderItem)
            ->exists();
    }
}
