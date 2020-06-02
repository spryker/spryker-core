<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use DateTime;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationChangeVersionTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsTransitionLogTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsQueryContainer extends AbstractQueryContainer implements OmsQueryContainerInterface
{
    public const VERSION = 'version';
    public const ID_OMS_PRODUCT_RESERVATION = 'idOmsProductReservation';
    public const RESERVATION_QUANTITY = 'reservationQuantity';
    public const SKU = 'sku';
    public const STORE = 'store';
    public const ID_OMS_PRODUCT_RESERVATION_STORE = 'idOmsProductReservationStore';
    public const LAST_UPDATE = 'lastUpdate';
    public const ITEMS_COUNT = 'itemsCount';
    public const DATE_WINDOW = 'dateWindow';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $states
     * @param string $processName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByState(array $states, $processName)
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->joinProcess(null, Criteria::INNER_JOIN)
            ->joinState(null, Criteria::INNER_JOIN)
            ->where('Process.name = ?', $processName)
            ->where("State.name IN ('" . implode("', '", $states) . "')");
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdOrder($idOrder)
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterByFkSalesOrder($idOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder)
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItemsByIdSalesOrder($idOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogForOrder(SpySalesOrder $order)
    {
        return $this->getFactory()
            ->createOmsTransitionLogQuery()
            ->filterByOrder($order)
            ->orderBy(SpyOmsTransitionLogTableMap::COL_ID_OMS_TRANSITION_LOG, Criteria::DESC);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     * @param bool $orderById
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogByIdOrder($idOrder, $orderById = true)
    {
        $transitionLogQuery = $this->getFactory()
            ->createOmsTransitionLogQuery()
            ->filterByFkSalesOrder($idOrder);

        if ($orderById) {
            $transitionLogQuery->orderBy(SpyOmsTransitionLogTableMap::COL_ID_OMS_TRANSITION_LOG, Criteria::DESC);
        }

        return $transitionLogQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \DateTime $now
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithExpiredTimeouts(DateTime $now)
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->joinEventTimeout()
            ->where('EventTimeout.timeout < ?', $now)
            ->withColumn('EventTimeout.event', 'event');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Oms\Persistence\OmsRepository::getSalesOrderItemsBySkuAndStatesNames()} instead.
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function sumProductQuantitiesForAllSalesOrderItemsBySku(array $states, $sku, $returnTest = true)
    {
        $salesOrderItemQuery = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_QUANTITY . ')', 'Sum')
            ->select(['Sum']);

        if ($returnTest === false) {
            $salesOrderItemQuery->useOrderQuery()
                ->filterByIsTest(false)
                ->endUse();
        }

        $stateNames = [];
        foreach ($states as $state) {
            $stateNames[] = $state->getName();
        }

        $salesOrderItemQuery->useStateQuery()
            ->filterByName($stateNames, Criteria::IN)
            ->endUse()
            ->filterBySku($sku);

        return $salesOrderItemQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Oms\Persistence\OmsRepository::getSalesOrderItemsBySkuAndStatesNames()} instead.
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param string $storeName
     * @param bool $returnTest
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function sumProductQuantitiesForAllSalesOrderItemsBySkuForStore(
        array $states,
        $sku,
        $storeName,
        $returnTest = true
    ) {
        return $this->sumProductQuantitiesForAllSalesOrderItemsBySku($states, $sku, $returnTest)
            ->useOrderQuery()
            ->filterByStore($storeName)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $query = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem();

        if ($returnTest === false) {
            $query->useOrderQuery()->filterByIsTest(false)->endUse();
        }

        $stateNames = [];
        foreach ($states as $state) {
            $stateNames[] = $state->getName();
        }

        $query->useStateQuery()->filterByName($stateNames, Criteria::IN)->endUse();
        $query->filterBySku($sku);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $orderItemIds
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItems(array $orderItemIds)
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterByIdSalesOrderItem($orderItemIds, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder)
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrder()
            ->filterByIdSalesOrder($idOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $activeProcesses
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function queryActiveProcesses(array $activeProcesses)
    {
        $query = $this->getFactory()
            ->createOmsOrderProcessQuery();

        return $query->filterByName($activeProcesses, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function queryOrderItemStates(array $orderItemStates)
    {
        $query = $this->getFactory()
            ->createOmsOrderItemStateQuery();

        return $query->filterByIdOmsOrderItemState($orderItemStates, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link queryGroupedMatrixOrderItems()} instead.
     *
     * @param array $processIds
     * @param array $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryMatrixOrderItems(array $processIds, array $stateBlacklist)
    {
        $query = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->filterByFkOmsOrderProcess($processIds, Criteria::IN);

        if ($stateBlacklist) {
            $query->filterByFkOmsOrderItemState($stateBlacklist, Criteria::NOT_IN);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $processIds
     * @param int[] $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryGroupedMatrixOrderItems(array $processIds, array $stateBlacklist): SpySalesOrderItemQuery
    {
        $query = $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem()
            ->withColumn('COUNT(*)', static::ITEMS_COUNT)
            ->withColumn(sprintf(
                "(CASE WHEN %s > '%s' THEN 'day' WHEN %s > '%s' THEN 'week' ELSE 'other' END)",
                SpySalesOrderItemTableMap::COL_LAST_STATE_CHANGE,
                (new DateTime('-1 day'))->format('Y-m-d H:i:s'),
                SpySalesOrderItemTableMap::COL_LAST_STATE_CHANGE,
                (new DateTime('-7 day'))->format('Y-m-d H:i:s')
            ), static::DATE_WINDOW)
            ->select([
                SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE,
                SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_PROCESS,
                static::ITEMS_COUNT,
                static::DATE_WINDOW,
            ])
            ->filterByFkOmsOrderProcess($processIds, Criteria::IN)
            ->groupBy(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_ITEM_STATE)
            ->groupBy(SpySalesOrderItemTableMap::COL_FK_OMS_ORDER_PROCESS)
            ->groupBy(static::DATE_WINDOW);

        if ($stateBlacklist) {
            $query->filterByFkOmsOrderItemState($stateBlacklist, Criteria::NOT_IN);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function querySalesOrderItemStatesByName(array $orderItemStates)
    {
        return $this->getFactory()
            ->createOmsOrderItemStateQuery()
            ->filterByName($orderItemStates, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(DateTime $expirationDate)
    {
        return $this->getFactory()
            ->createOmsStateMachineLockQuery()
            ->filterByExpires(['max' => $expirationDate], Criteria::LESS_EQUAL);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier)
    {
        return $this->getFactory()
            ->createOmsStateMachineLockQuery()
            ->filterByIdentifier($identifier);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryOmsProductReservationBySku($sku)
    {
        return $this->getFactory()
            ->createOmsProductReservationQuery()
            ->filterBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryProductReservationBySkuAndStore($sku, $idStore)
    {
        return $this->getFactory()
            ->createOmsProductReservationQuery()
            ->filterBySku($sku)
            ->filterByFkStore($idStore);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $storeName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryOmsProductReservationStoreBySkuForStore($sku, $storeName)
    {
        return $this->getFactory()->createOmsProductReservationStoreQuery()
            ->filterBySku($sku)
            ->filterByStore($storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryOmsProductReservationStoreBySku($sku)
    {
        return $this->getFactory()
            ->createOmsProductReservationStoreQuery()
            ->filterBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryMaxReservationChangeVersion()
    {
        return $this->getFactory()
            ->createOmsProductReservationChangeVersionQuery()
            ->withColumn(
                sprintf(
                    'MAX(%s)',
                    SpyOmsProductReservationChangeVersionTableMap::COL_VERSION
                ),
                static::VERSION
            )
            ->select([static::VERSION]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $lastExportedVersion
     * @param int $maxVisibleVersion
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery
     */
    public function queryReservationChangeVersion($lastExportedVersion, $maxVisibleVersion)
    {
        /** @var \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery $query */
        $query = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->addJoin(
                SpyOmsProductReservationTableMap::COL_ID_OMS_PRODUCT_RESERVATION,
                SpyOmsProductReservationChangeVersionTableMap::COL_ID_OMS_PRODUCT_RESERVATION_ID,
                Criteria::LEFT_JOIN
            )
            ->withColumn(SpyOmsProductReservationTableMap::COL_ID_OMS_PRODUCT_RESERVATION, static::ID_OMS_PRODUCT_RESERVATION)
            ->withColumn(SpyOmsProductReservationTableMap::COL_SKU, static::SKU)
            ->withColumn(SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY, static::RESERVATION_QUANTITY)
            ->withColumn(sprintf('MAX(%s)', SpyOmsProductReservationChangeVersionTableMap::COL_VERSION), static::VERSION)
            ->select([
                static::ID_OMS_PRODUCT_RESERVATION,
                static::SKU,
                static::RESERVATION_QUANTITY,
                static::VERSION,
            ])
            ->groupBy(static::ID_OMS_PRODUCT_RESERVATION)
            ->where(static::VERSION . ' > ' . $this->getConnection()->quote($lastExportedVersion))
            ->where(static::VERSION . ' <= ' . $this->getConnection()->quote($maxVisibleVersion));

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersionQuery
     */
    public function queryOmsProductReservationLastExportedVersion()
    {
        return $this->getFactory()->createOmsProductReservationExportedVersionQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idOmsProductReservation
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryOmsProductReservationById($idOmsProductReservation)
    {
        return $this->getFactory()->createOmsProductReservationQuery()->filterByIdOmsProductReservation($idOmsProductReservation);
    }
}
