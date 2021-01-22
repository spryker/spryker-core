<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderProcessTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsRepository extends AbstractRepository implements OmsRepositoryInterface
{
    protected const COL_PRODUCT_RESERVATION_TOTAL_QUANTITY = 'productReservationTotalQuantity';

    /**
     * @param int[] $processIds
     * @param int[] $stateBlackList
     *
     * @return array
     */
    public function getMatrixOrderItems(array $processIds, array $stateBlackList): array
    {
        $orderItemsMatrixResult = $this->getFactory()->getOmsQueryContainer()
            ->queryGroupedMatrixOrderItems($processIds, $stateBlackList)
            ->find();

        return $this->getFactory()
            ->createOrderItemMapper()
            ->mapOrderItemMatrix($orderItemsMatrixResult->getArrayCopy());
    }

    /**
     * @param string[] $stateNames
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    public function getSalesOrderAggregationBySkuAndStatesNames(array $stateNames, string $sku, ?StoreTransfer $storeTransfer = null): array
    {
        $salesOrderItemQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterBySku($sku)
            ->useStateQuery()
                ->filterByName_In($stateNames)
            ->endUse()
            ->groupByFkOmsOrderItemState()
            ->innerJoinProcess()
            ->groupByFkOmsOrderProcess()
            ->withColumn(SpySalesOrderItemTableMap::COL_SKU, SalesOrderItemStateAggregationTransfer::SKU)
            ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::PROCESS_NAME)
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, SalesOrderItemStateAggregationTransfer::STATE_NAME)
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_QUANTITY . ')', SalesOrderItemStateAggregationTransfer::SUM_AMOUNT)
            ->select([
                SpySalesOrderItemTableMap::COL_SKU,
            ]);

        if ($storeTransfer !== null) {
            $storeTransfer->requireName();

            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByStore($storeTransfer->getName())
                ->endUse();
        }

        /** @var array $salesOrderItemAggregations */
        $salesOrderItemAggregations = $salesOrderItemQuery->find();

        $salesAggregationTransfers = [];
        foreach ($salesOrderItemAggregations as $salesOrderItemAggregation) {
            $salesAggregationTransfers[] = (new SalesOrderItemStateAggregationTransfer())
                ->fromArray($salesOrderItemAggregation, true);
        }

        return $salesAggregationTransfers;
    }

    /**
     * @param string[] $concreteSkus
     * @param int $idStore
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getSumOmsReservedProductQuantityByConcreteProductSkusForStore(array $concreteSkus, int $idStore): Decimal
    {
        $productReservationTotalQuantity = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->select([static::COL_PRODUCT_RESERVATION_TOTAL_QUANTITY])
            ->filterByFkStore($idStore)
            ->filterBySku_In($concreteSkus)
            ->withColumn(sprintf('SUM(%s)', SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY), static::COL_PRODUCT_RESERVATION_TOTAL_QUANTITY)
            ->findOne();

        return new Decimal($productReservationTotalQuantity ?? 0);
    }

    /**
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer[]
     */
    public function getItemHistoryStatesByOrderItemIds(array $salesOrderItemIds): array
    {
        $omsOrderItemStateHistoryQuery = $this->getFactory()
            ->createOmsOrderItemStateHistoryQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->leftJoinWithState()
            ->leftJoinOrderItem()
            ->groupByFkSalesOrderItem()
            ->groupByIdOmsOrderItemStateHistory()
            ->orderByIdOmsOrderItemStateHistory(Criteria::DESC);

        return $this->getFactory()
            ->createOrderItemMapper()
            ->mapOmsOrderItemStateHistoryEntityCollectionToItemStateHistoryTransfers($omsOrderItemStateHistoryQuery->find());
    }

    /**
     * @module Sales
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): array
    {
        $salesOrderItemQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->joinWithState()
            ->joinWithProcess();

        $salesOrderItemQuery = $this->setSalesOrderItemQueryFilters(
            $salesOrderItemQuery,
            $orderItemFilterTransfer
        );

        return $this->getFactory()
            ->createOrderItemMapper()
            ->mapSalesOrderItemEntityCollectionToOrderItemTransfers($salesOrderItemQuery->find());
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function setSalesOrderItemQueryFilters(
        SpySalesOrderItemQuery $salesOrderItemQuery,
        OrderItemFilterTransfer $orderItemFilterTransfer
    ): SpySalesOrderItemQuery {
        if ($orderItemFilterTransfer->getSalesOrderItemIds()) {
            $salesOrderItemQuery->filterByIdSalesOrderItem_In($orderItemFilterTransfer->getSalesOrderItemIds());
        }

        if ($orderItemFilterTransfer->getSalesOrderIds()) {
            $salesOrderItemQuery->filterByFkSalesOrder_In($orderItemFilterTransfer->getSalesOrderIds());
        }

        if ($orderItemFilterTransfer->getOrderReferences()) {
            $salesOrderItemQuery
                ->useOrderQuery()
                    ->filterByOrderReference_In($orderItemFilterTransfer->getOrderReferences())
                ->endUse();
        }

        return $salesOrderItemQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OmsProductReservationTransfer|null
     */
    public function findProductReservation(ReservationRequestTransfer $reservationRequestTransfer): ?OmsProductReservationTransfer
    {
        $reservationRequestTransfer->requireSku()
            ->requireStore()
            ->getStore()
                ->requireIdStore();

        $omsProductReservationEntity = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->filterBySku($reservationRequestTransfer->getSku())
            ->filterByFkStore($reservationRequestTransfer->getStore()->getIdStore())
            ->findOne();

        if (!$omsProductReservationEntity) {
            return null;
        }

        return $this->getFactory()
            ->createOmsMapper()
            ->mapOmsProductReservationEntityToOmsProductReservationTransfer(
                $omsProductReservationEntity,
                new OmsProductReservationTransfer()
            );
    }

    /**
     * @param string $sku
     * @param int $idStore
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function findProductReservationQuantity(string $sku, int $idStore): Decimal
    {
        $reservationEntity = $this->getFactory()->createOmsProductReservationQuery()
            ->filterBySku($sku)
            ->filterByFkStore($idStore)
            ->findOne();

        if ($reservationEntity) {
            return new Decimal($reservationEntity->getReservationQuantity());
        }

        return new Decimal(0);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer[]
     */
    public function findProductReservationStores(string $sku): array
    {
        $omsProductReservationStoreEntities = $this->getFactory()
            ->createOmsProductReservationStoreQuery()
            ->filterBySku($sku)
            ->find();

        $reservationResponseTransfers = [];
        $omsMapper = $this->getFactory()->createOmsMapper();

        foreach ($omsProductReservationStoreEntities as $omsProductReservationStoreEntity) {
            $reservationResponseTransfers[] = $omsMapper->mapOmsProductReservationStoreEntityToReservationResponseTransfer(
                $omsProductReservationStoreEntity,
                new ReservationResponseTransfer()
            );
        }

        return $reservationResponseTransfers;
    }
}
