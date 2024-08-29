<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use DateTime;
use Generated\Shared\Transfer\OmsProductReservationTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderMatrixCollectionTransfer;
use Generated\Shared\Transfer\OrderMatrixCriteriaTransfer;
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
    /**
     * @var string
     */
    protected const ITEMS_COUNT = 'itemsCount';

    /**
     * @var string
     */
    protected const DATE_WINDOW = 'dateWindow';

    /**
     * @var string
     */
    protected const COL_PRODUCT_RESERVATION_TOTAL_QUANTITY = 'productReservationTotalQuantity';

    /**
     * @var string
     */
    protected const STATE_NAME = 'stateName';

    /**
     * @var string
     */
    protected const PROCESS_NAME = 'processName';

    /**
     * @var string
     */
    protected const DATE_CASE_EXPRESSION = "(CASE WHEN %s > '%s' THEN 'day' WHEN %s > '%s' THEN 'week' ELSE 'other' END)";

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const FK_OMS_ORDER_ITEM_STATE = 'fk_oms_order_item_state';

    /**
     * @var string
     */
    protected const FK_OMS_ORDER_PROCESS = 'fk_oms_order_process';

    /**
     * @var string
     */
    protected const SUBQUERY_ALIAS = 'sub';

    /**
     * @var string
     */
    protected const SUBQUERY_FIELD_PLACEHOLDER = '%s.%s';

    /**
     * @var string
     */
    protected const COUNT_ALL_KEYWORD = 'COUNT(*)';

    /**
     * @deprecated Use {@link \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface::getOrderMatrixCollection()} instead.
     *
     * @param array<int> $processIds
     * @param array<int> $stateBlackList
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
     * @param \Generated\Shared\Transfer\OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\OrderMatrixCollectionTransfer
     */
    public function getOrderMatrixCollection(OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer): OrderMatrixCollectionTransfer
    {
        $subQuery = $this->getOrderMatrixSubquery($orderMatrixCriteriaTransfer);

        $query = $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->addSelectQuery($subQuery, static::SUBQUERY_ALIAS)
            ->select([
                sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::FK_OMS_ORDER_ITEM_STATE),
                sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::FK_OMS_ORDER_PROCESS),
            ])
            ->withColumn(static::COUNT_ALL_KEYWORD, static::ITEMS_COUNT)
            ->withColumn(sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::DATE_WINDOW), static::DATE_WINDOW)
            ->withColumn(sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::PROCESS_NAME), static::PROCESS_NAME)
            ->withColumn(sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::STATE_NAME), static::STATE_NAME)
            ->groupBy([
                sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::FK_OMS_ORDER_ITEM_STATE),
                sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::FK_OMS_ORDER_PROCESS),
            ])
            ->addGroupByColumn(sprintf(static::SUBQUERY_FIELD_PLACEHOLDER, static::SUBQUERY_ALIAS, static::DATE_WINDOW));

        $orderItemEntities = $query->find()->getArrayCopy();

        return $this->getFactory()
            ->createOrderItemMapper()
            ->mapSalesOrderItemEntitiesToOrderMatrixCollectionTransfer($orderItemEntities, new OrderMatrixCollectionTransfer());
    }

    /**
     * @return array<int, string>
     */
    public function getProcessNamesIndexedByIdOmsOrderProcess(): array
    {
        $activeProcesses = $this->getFactory()
            ->getConfig()
            ->getActiveProcesses();
        $query = $this->getFactory()
            ->createOmsOrderProcessQuery();

        $processEntities = $query->filterByName($activeProcesses, Criteria::IN)
            ->find()
            ->getArrayCopy();

        return $this->getFactory()
            ->createProcessIndexer()
            ->getProcessNamesIndexedByIdOmsOrderProcess($processEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getOrderMatrixSubquery(OrderMatrixCriteriaTransfer $orderMatrixCriteriaTransfer): SpySalesOrderItemQuery
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $subQuery */
        $subQuery = $this->getFactory()
            ->getSalesOrderItemPropelQuery();

        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $subQuery */
        $subQuery = $subQuery->filterByFkOmsOrderProcess($orderMatrixCriteriaTransfer->getOrderMatrixConditions()->getProcessIds(), Criteria::IN)
            ->useStateQuery()
            ->withColumn(SpyOmsOrderItemStateTableMap::COL_NAME, static::STATE_NAME)
            ->endUse();

        $subQuery->useProcessQuery()
                ->withColumn(SpyOmsOrderProcessTableMap::COL_NAME, static::PROCESS_NAME)
            ->endUse()
            ->withColumn(sprintf(
                static::DATE_CASE_EXPRESSION,
                SpySalesOrderItemTableMap::COL_LAST_STATE_CHANGE,
                (new DateTime('-1 day'))->format(static::DATE_FORMAT),
                SpySalesOrderItemTableMap::COL_LAST_STATE_CHANGE,
                (new DateTime('-7 day'))->format(static::DATE_FORMAT),
            ), static::DATE_WINDOW)
            ->withColumn(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_PROCESS, static::FK_OMS_ORDER_PROCESS)
            ->withColumn(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE, static::FK_OMS_ORDER_ITEM_STATE);

        if ($orderMatrixCriteriaTransfer->getPagination() && $orderMatrixCriteriaTransfer->getPagination()->getLimit()) {
            $subQuery->limit($orderMatrixCriteriaTransfer->getPagination()->getLimit())
                ->offset($orderMatrixCriteriaTransfer->getPagination()->getOffset());
        }
        $stateBlackList = $this->getFactory()->getConfig()->getStateBlacklist();

        if ($stateBlackList) {
            $subQuery->useStateQuery()
                ->filterByName($stateBlackList, Criteria::NOT_IN)
            ->endUse();
        }

        return $subQuery;
    }

    /**
     * @param array<string> $stateNames
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer>
     */
    public function getSalesOrderAggregationBySkuAndStatesNames(array $stateNames, string $sku, ?StoreTransfer $storeTransfer = null): array
    {
        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery */
        $salesOrderItemQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterBySku($sku)
            ->useStateQuery()
                ->filterByName_In($stateNames)
            ->endUse();

        /** @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $salesOrderItemQuery */
        $salesOrderItemQuery = $salesOrderItemQuery
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
     * @param array<string> $concreteSkus
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
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemStateTransfer>
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
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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
            $orderItemFilterTransfer,
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
                new OmsProductReservationTransfer(),
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
     * @return array<\Generated\Shared\Transfer\ReservationResponseTransfer>
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
                new ReservationResponseTransfer(),
            );
        }

        return $reservationResponseTransfers;
    }
}
