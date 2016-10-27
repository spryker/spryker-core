<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Persistence;

use DateTime;
use Orm\Zed\Oms\Persistence\Map\SpyOmsTransitionLogTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Oms\OmsDependencyProvider;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsQueryContainer extends AbstractQueryContainer implements OmsQueryContainerInterface
{

    /**
     * @api
     *
     * @param array $states
     * @param string $processName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByState(array $states, $processName)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItem()
            ->joinProcess(null, Criteria::INNER_JOIN)
            ->joinState(null, Criteria::INNER_JOIN)
            ->where('Process.name = ?', $processName)
            ->where("State.name IN ('" . implode("', '", $states) . "')");
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainer()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::QUERY_CONTAINER_SALES);
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdOrder($idOrder)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItem()
            ->filterByFkSalesOrder($idOrder);
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItemsByIdSalesOrder($idOrder);
    }

    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogForOrder(SpySalesOrder $order)
    {
        return $this->getFactory()->createOmsTransitionLogQuery()
            ->filterByOrder($order)
            ->orderBy(SpyOmsTransitionLogTableMap::COL_ID_OMS_TRANSITION_LOG, Criteria::DESC);
    }

    /**
     * @api
     *
     * @param int $idOrder
     * @param bool $orderById
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery
     */
    public function queryLogByIdOrder($idOrder, $orderById = true)
    {
        $transitionLogQuery = $this->getFactory()->createOmsTransitionLogQuery();

        $transitionLogQuery
            ->filterByFkSalesOrder($idOrder);

        if ($orderById) {
            $transitionLogQuery->orderBy(SpyOmsTransitionLogTableMap::COL_ID_OMS_TRANSITION_LOG, Criteria::DESC);
        }

        return $transitionLogQuery;
    }

    /**
     * @api
     *
     * @param \DateTime $now
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithExpiredTimeouts(DateTime $now)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItem()
            ->joinEventTimeout()
            ->where('EventTimeout.timeout < ?', $now)
            ->withColumn('EventTimeout.event', 'event');
    }

    /**
     * @api
     *
     * @deprecated Use sumProductQuantitiesForAllSalesOrderItemsBySku($states, $sku, $returnTest) instead.
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function countSalesOrderItemsForSku(array $states, $sku, $returnTest = true)
    {
        $query = $this->getSalesQueryContainer()->querySalesOrderItem();
        $query->withColumn('COUNT(*)', 'Count')->select(['Count']);

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
     * @api
     *
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function sumProductQuantitiesForAllSalesOrderItemsBySku(array $states, $sku, $returnTest = true)
    {
        $query = $this->getSalesQueryContainer()->querySalesOrderItem();
        $query->withColumn('SUM(' . SpySalesOrderItemTableMap::COL_QUANTITY . ')', 'Sum')->select(['Sum']);

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
        $query = $this->getSalesQueryContainer()->querySalesOrderItem();

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
     * @api
     *
     * @param array $orderItemIds
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItems(array $orderItemIds)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItem()
            ->filterByIdSalesOrderItem($orderItemIds, Criteria::IN);
    }

    /**
     * @api
     *
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder)
    {
        return $this->getSalesQueryContainer()->querySalesOrder()
            ->filterByIdSalesOrder($idOrder);
    }

    /**
     * @api
     *
     * @param string[] $activeProcesses
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function getActiveProcesses(array $activeProcesses)
    {
        $query = $this->getFactory()->createOmsOrderProcessQuery();

        return $query->filterByName($activeProcesses, Criteria::IN);
    }

    /**
     * @api
     *
     * @param array $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function getOrderItemStates(array $orderItemStates)
    {
        $query = $this->getFactory()->createOmsOrderItemStateQuery();

        return $query->filterByIdOmsOrderItemState($orderItemStates, Criteria::IN);
    }

    /**
     * @api
     *
     * @param array $processIds
     * @param array $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryMatrixOrderItems(array $processIds, array $stateBlacklist)
    {
        $query = $this->getFactory()->createSalesOrderItemQuery();
        $query->filterByFkOmsOrderProcess($processIds, Criteria::IN);
        if ($stateBlacklist) {
            $query->filterByFkOmsOrderItemState($stateBlacklist, Criteria::NOT_IN);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param string[] $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function querySalesOrderItemStatesByName(array $orderItemStates)
    {
        $query = $this->getFactory()->createOmsOrderItemStateQuery();
        $query->filterByName($orderItemStates, Criteria::IN);

        return $query;
    }

    /**
     * @api
     *
     * @deprecated Not used, will be removed in the next major release.
     *
     * @param string $identifier
     * @param \DateTime $expirationDate
     *
     * @return $this|\Orm\Zed\StateMachine\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockedItemsByIdentifierAndExpirationDate($identifier, \DateTime $expirationDate)
    {
        return $this->getFactory()
            ->createOmsStateMachineLockQuery()
            ->filterByIdentifier($identifier)
            ->filterByExpires(['min' => $expirationDate], Criteria::GREATER_EQUAL);
    }

    /**
     * @api
     *
     * @param \DateTime $expirationDate
     *
     * @return $this|\Orm\Zed\StateMachine\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockedItemsByExpirationDate(\DateTime $expirationDate)
    {
        return $this->getFactory()
            ->createOmsStateMachineLockQuery()
            ->filterByExpires(['max' => $expirationDate], Criteria::LESS_EQUAL);
    }

    /**
     * @api
     *
     * @param string $identifier
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyOmsStateMachineLockQuery
     */
    public function queryLockItemsByIdentifier($identifier)
    {
        return $this->getFactory()
            ->createOmsStateMachineLockQuery()
            ->filterByIdentifier($identifier);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function createOmsProductReservationQuery($sku)
    {
        return $this->getFactory()->createOmsProductReservationQuery()
            ->filterBySku($sku);
    }

}
