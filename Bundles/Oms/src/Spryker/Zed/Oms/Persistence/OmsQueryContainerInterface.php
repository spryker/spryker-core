<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use DateTime;
use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface OmsQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $states
     * @param string $processName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByState(array $states, $processName);

    /**
     * Specification:
     * - Returns an optimized query based on store name and limit to fetch sales order items.
     *
     * @api
     *
     * @param int $idOmsOrderProcess
     * @param array $omsOrderItemStateIds
     * @param \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByProcessIdStateIdsAndQueryCriteria(
        int $idOmsOrderProcess,
        array $omsOrderItemStateIds,
        OmsCheckConditionsQueryCriteriaTransfer $omsCheckConditionsQueryCriteriaTransfer
    ): SpySalesOrderItemQuery;

    /**
     * Specification:
     * - Returns a query to find a process by it's name.
     *
     * @api
     *
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function queryProcess(string $processName): SpyOmsOrderProcessQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdOrder($idOrder);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogForOrder(SpySalesOrder $order);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idOrder
     * @param bool $orderById
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogByIdOrder($idOrder, $orderById = true);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \DateTime $now
     * @param \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer|null $omsCheckTimeoutsQueryCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithExpiredTimeouts(
        DateTime $now,
        ?OmsCheckTimeoutsQueryCriteriaTransfer $omsCheckTimeoutsQueryCriteriaTransfer = null
    );

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface::getSalesOrderItemsBySkuAndStatesNames()} instead.
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function sumProductQuantitiesForAllSalesOrderItemsBySku(array $states, $sku, $returnTest = true);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface::getSalesOrderItemsBySkuAndStatesNames()} instead.
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
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $orderItemIds
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItems(array $orderItemIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string[] $activeProcesses
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function queryActiveProcesses(array $activeProcesses);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function queryOrderItemStates(array $orderItemStates);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link queryGroupedMatrixOrderItems()} instead
     *
     * @param array $processIds
     * @param array $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryMatrixOrderItems(array $processIds, array $stateBlacklist);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $processIds
     * @param int[] $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryGroupedMatrixOrderItems(array $processIds, array $stateBlacklist): SpySalesOrderItemQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string[] $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function querySalesOrderItemStatesByName(array $orderItemStates);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(DateTime $expirationDate);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryOmsProductReservationBySku($sku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryProductReservationBySkuAndStore($sku, $idStore);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param string $storeName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryOmsProductReservationStoreBySkuForStore($sku, $storeName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationStoreQuery
     */
    public function queryOmsProductReservationStoreBySku($sku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery
     */
    public function queryMaxReservationChangeVersion();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $lastExportedVersion
     * @param int $maxVisibleVersion
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersionQuery
     */
    public function queryReservationChangeVersion($lastExportedVersion, $maxVisibleVersion);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersionQuery
     */
    public function queryOmsProductReservationLastExportedVersion();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idOmsProductReservation
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function queryOmsProductReservationById($idOmsProductReservation);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $identifiers
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockItemsByIdentifiers(array $identifiers): SpyOmsStateMachineLockQuery;
}
