<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderItemTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
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
            $merchantSalesOrderQuery,
        );
        $merchantSalesOrderQuery = $this->applyMerchantOrderFilters($merchantSalesOrderQuery, $merchantOrderCriteriaTransfer);

        /** @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder> $merchantSalesOrderQuery */
        $merchantSalesOrderQuery = $this->buildQueryFromCriteria(
            $merchantSalesOrderQuery,
            $merchantOrderCriteriaTransfer->getFilter(),
        );
        $merchantSalesOrderQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder> $merchantSalesOrderEntityCollection */
        $merchantSalesOrderEntityCollection = $this
            ->applyMerchantOrderPagination($merchantSalesOrderQuery, $merchantOrderCriteriaTransfer->getPagination())
            ->find();

        if ($merchantSalesOrderEntityCollection->count() === 0) {
            return new MerchantOrderCollectionTransfer();
        }

        /** @var array<\Generated\Shared\Transfer\MerchantOrderTransfer> $merchantOrderTransfers */
        $merchantOrderTransfers = [];
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        foreach ($merchantSalesOrderEntityCollection as $merchantSalesOrderEntity) {
            $merchantOrderTransfers[$merchantSalesOrderEntity->getIdMerchantSalesOrder()] = $merchantSalesOrderMapper
                ->mapMerchantSalesOrderEntityToMerchantOrderTransfer(
                    $merchantSalesOrderEntity,
                    new MerchantOrderTransfer(),
                );
        }

        if ($merchantOrderCriteriaTransfer->getWithItems()) {
            $merchantOrderTransfers = $this->addMerchantOrderItemsToMerchantOrders($merchantOrderTransfers);
        }

        return (new MerchantOrderCollectionTransfer())->setMerchantOrders(
            new ArrayObject(array_values($merchantOrderTransfers)),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\MerchantOrderTransfer> $merchantOrderTransfers
     *
     * @return array<\Generated\Shared\Transfer\MerchantOrderTransfer>
     */
    protected function addMerchantOrderItemsToMerchantOrders(array $merchantOrderTransfers): array
    {
        $merchantSalesOrderItemEntityCollection = $this->getMerchantSalesOrderItemEntityCollectionByMerchantOrderIds(
            array_keys($merchantOrderTransfers),
        );

        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        foreach ($merchantSalesOrderItemEntityCollection as $merchantSalesOrderItemEntity) {
            /** @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity */
            $merchantOrderTransfers[$merchantSalesOrderItemEntity->getFkMerchantSalesOrder()]->addMerchantOrderItem(
                $merchantSalesOrderMapper->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
                    $merchantSalesOrderItemEntity,
                    new MerchantOrderItemTransfer(),
                ),
            );
        }

        return $merchantOrderTransfers;
    }

    /**
     * @param array<int> $merchantOrderIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem>
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
            $merchantSalesOrderQuery,
        );
        $merchantSalesOrderEntity = $this
            ->applyMerchantOrderFilters($merchantSalesOrderQuery, $merchantOrderCriteriaTransfer)
            ->findOne();

        if (!$merchantSalesOrderEntity) {
            return null;
        }

        if ($merchantOrderCriteriaTransfer->getWithItems()) {
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(SpyMerchantSalesOrderItemTableMap::COL_ID_MERCHANT_SALES_ORDER_ITEM);

            $merchantSalesOrderItems = $merchantSalesOrderEntity->getMerchantSalesOrderItems($criteria);
            $merchantSalesOrderEntity->setMerchantSalesOrderItems($merchantSalesOrderItems);
        }

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderEntityToMerchantOrderTransfer($merchantSalesOrderEntity, new MerchantOrderTransfer());
    }

    /**
     * @module Sales
     *
     * @param int $idMerchantOrder
     *
     * @return int
     */
    public function getUniqueProductsCount(int $idMerchantOrder): int
    {
        return $this->getFactory()
            ->createMerchantSalesOrderItemQuery()
            ->filterByFkMerchantSalesOrder($idMerchantOrder)
            ->useSalesOrderItemQuery()
                ->groupBySku()
            ->endUse()
            ->withColumn(SpySalesOrderItemTableMap::COL_SKU)
            ->select([SpySalesOrderItemTableMap::COL_SKU])
            ->count();
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder> $merchantSalesOrderQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder>
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
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder> $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder>
     */
    protected function applyMerchantOrderFilters(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
    ): SpyMerchantSalesOrderQuery {
        if ($merchantOrderCriteriaTransfer->getIdMerchantOrder() !== null) {
            $merchantSalesOrderQuery->filterByIdMerchantSalesOrder(
                $merchantOrderCriteriaTransfer->getIdMerchantOrder(),
            );
        }

        if ($merchantOrderCriteriaTransfer->getMerchantOrderReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantSalesOrderReference(
                $merchantOrderCriteriaTransfer->getMerchantOrderReference(),
            );
        }

        if ($merchantOrderCriteriaTransfer->getMerchantOrderReferences()) {
            $merchantSalesOrderQuery->filterByMerchantSalesOrderReference_In(
                $merchantOrderCriteriaTransfer->getMerchantOrderReferences(),
            );
        }

        if ($merchantOrderCriteriaTransfer->getMerchantReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantReference(
                $merchantOrderCriteriaTransfer->getMerchantReference(),
            );
        }

        if ($merchantOrderCriteriaTransfer->getIdOrder() !== null) {
            $merchantSalesOrderQuery->filterByFkSalesOrder(
                $merchantOrderCriteriaTransfer->getIdOrder(),
            );
        }

        if ($merchantOrderCriteriaTransfer->getIdMerchant() !== null) {
            $merchantSalesOrderQuery->addJoin(
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                Criteria::INNER_JOIN,
            );
            $merchantSalesOrderQuery->addAnd(
                SpyMerchantTableMap::COL_ID_MERCHANT,
                $merchantOrderCriteriaTransfer->getIdMerchant(),
            );
        }

        if ($merchantOrderCriteriaTransfer->getOrderItemUuids()) {
            $merchantSalesOrderQuery->addJoin(
                SpyMerchantSalesOrderTableMap::COL_ID_MERCHANT_SALES_ORDER,
                SpyMerchantSalesOrderItemTableMap::COL_FK_MERCHANT_SALES_ORDER,
                Criteria::INNER_JOIN,
            );
            $merchantSalesOrderQuery->addJoin(
                SpyMerchantSalesOrderItemTableMap::COL_FK_SALES_ORDER_ITEM,
                SpySalesOrderItemTableMap::COL_ID_SALES_ORDER_ITEM,
                Criteria::INNER_JOIN,
            );
            $merchantSalesOrderQuery->addAnd(
                SpySalesOrderItemTableMap::COL_UUID,
                $merchantOrderCriteriaTransfer->getOrderItemUuids(),
                Criteria::IN,
            );
        }

        if ($merchantOrderCriteriaTransfer->getCustomerReference()) {
            $merchantSalesOrderQuery
                ->useOrderQuery()
                    ->filterByCustomerReference($merchantOrderCriteriaTransfer->getCustomerReference())
                ->endUse();
        }

        if ($merchantOrderCriteriaTransfer->getMerchantReferences()) {
            $merchantSalesOrderQuery->filterByMerchantReference_In(
                $merchantOrderCriteriaTransfer->getMerchantReferences(),
            );
        }

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder> $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder>
     */
    protected function applyMerchantOrderPagination(
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

        /** @var \Propel\Runtime\Util\PropelModelPager<mixed> $paginationModel */
        $paginationModel = $merchantSalesOrderQuery->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        /** @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder> $query */
        $query = $paginationModel->getQuery();

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer|null
     */
    public function findMerchantOrderItem(
        MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
    ): ?MerchantOrderItemTransfer {
        $merchantSalesOrderItemQuery = $this->getFactory()->createMerchantSalesOrderItemQuery();
        $merchantSalesOrderItemQuery = $this->applyMerchantOrderItemFilters(
            $merchantOrderItemCriteriaTransfer,
            $merchantSalesOrderItemQuery,
        );

        $merchantSalesOrderItemEntity = $merchantSalesOrderItemQuery->findOne();

        if (!$merchantSalesOrderItemEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
                $merchantSalesOrderItemEntity,
                new MerchantOrderItemTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return int
     */
    public function getMerchantOrdersCount(MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer): int
    {
        return $this->applyMerchantOrderFilters(
            $this->getFactory()->createMerchantSalesOrderQuery(),
            $merchantOrderCriteriaTransfer,
        )->distinct()->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function getMerchantOrderItemCollection(MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer): MerchantOrderItemCollectionTransfer
    {
        $merchantSalesOrderItemQuery = $this->getFactory()->createMerchantSalesOrderItemQuery();
        $merchantSalesOrderItemQuery = $this->applyMerchantOrderItemFilters(
            $merchantOrderItemCriteriaTransfer,
            $merchantSalesOrderItemQuery,
        );

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem> $merchantSalesOrderEntities */
        $merchantSalesOrderEntities = $merchantSalesOrderItemQuery->find();

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderItemEntitiesToMerchantOrderItemCollectionTransfer(
                $merchantSalesOrderEntities,
                new MerchantOrderItemCollectionTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem> $merchantSalesOrderItemQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItemQuery<\Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem>
     */
    protected function applyMerchantOrderItemFilters(
        MerchantOrderItemCriteriaTransfer $merchantOrderItemCriteriaTransfer,
        SpyMerchantSalesOrderItemQuery $merchantSalesOrderItemQuery
    ): SpyMerchantSalesOrderItemQuery {
        if ($merchantOrderItemCriteriaTransfer->getIdMerchantOrderItem()) {
            $merchantSalesOrderItemQuery->filterByIdMerchantSalesOrderItem($merchantOrderItemCriteriaTransfer->getIdMerchantOrderItem());
        }
        if ($merchantOrderItemCriteriaTransfer->getMerchantOrderItemIds()) {
            $merchantSalesOrderItemQuery->filterByIdMerchantSalesOrderItem_In($merchantOrderItemCriteriaTransfer->getMerchantOrderItemIds());
        }

        if ($merchantOrderItemCriteriaTransfer->getIdOrderItem()) {
            $merchantSalesOrderItemQuery->filterByFkSalesOrderItem($merchantOrderItemCriteriaTransfer->getIdOrderItem());
        }

        if ($merchantOrderItemCriteriaTransfer->getOrderItemIds()) {
            $merchantSalesOrderItemQuery->filterByFkSalesOrderItem_In($merchantOrderItemCriteriaTransfer->getOrderItemIds());
        }

        if ($merchantOrderItemCriteriaTransfer->getMerchantOrderItemReference() !== null) {
            $merchantSalesOrderItemQuery->filterByMerchantOrderItemReference(
                $merchantOrderItemCriteriaTransfer->getMerchantOrderItemReference(),
            );
        }

        return $merchantSalesOrderItemQuery;
    }
}
