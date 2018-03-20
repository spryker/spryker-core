<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use DateTime;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface OmsQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $states
     * @param string $processName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByState(array $states, $processName);

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdOrder($idOrder);

    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogForOrder(SpySalesOrder $order);

    /**
     * @api
     *
     * @param int $idOrder
     * @param bool $orderById
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogByIdOrder($idOrder, $orderById = true);

    /**
     * @api
     *
     * @param \DateTime $now
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithExpiredTimeouts(DateTime $now);

    /**
     * @api
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function sumProductQuantitiesForAllSalesOrderItemsBySku(array $states, $sku, $returnTest = true);

    /**
     * @api
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
    );

    /**
     * @api
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsForSku(array $states, $sku, $returnTest = true);

    /**
     * @api
     *
     * @param array $orderItemIds
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItems(array $orderItemIds);

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder);

    /**
     * @api
     *
     * @param string[] $activeProcesses
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function queryActiveProcesses(array $activeProcesses);

    /**
     * @api
     *
     * @param array $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function queryOrderItemStates(array $orderItemStates);

    /**
     * @api
     *
     * @param array $processIds
     * @param array $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryMatrixOrderItems(array $processIds, array $stateBlacklist);

    /**
     * @api
     *
     * @param string[] $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function querySalesOrderItemStatesByName(array $orderItemStates);

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(DateTime $expirationDate);

    /**
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryOmsProductReservationBySku($sku);

    /**
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryProductReservationBySkuAndStore($sku, $idStore);

    /**
     * @api
     *
     * @param string $sku
     * @param string $storeName
     *
     * @return $this|\Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryOmsProductReservationStoreBySkuForStore($sku, $storeName);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryOmsProductReservationStoreBySku($sku);

    /**
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryMaxReservationChangeVersion();

    /**
     * @api
     *
     * @param int $lastExportedVersion
     * @param int $maxVisibleVersion
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery
     */
    public function queryReservationChangeVersion($lastExportedVersion, $maxVisibleVersion);

    /**
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersionQuery
     */
    public function queryOmsProductReservationLastExportedVersion();

    /**
     * @api
     *
     * @param int $idOmsProductReservation
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryOmsProductReservationById($idOmsProductReservation);
}
