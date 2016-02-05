<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Persistence;

use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Orm\Zed\Oms\Persistence\Map\SpyOmsTransitionLogTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsPersistenceFactory getFactory()
 */
class OmsQueryContainer extends AbstractQueryContainer implements OmsQueryContainerInterface
{

    /**
     * @param array $states
     * @param string $processName
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByState(array $states, $processName)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItem()
            ->joinProcess(null, $joinType = Criteria::INNER_JOIN)
            ->joinState(null, $joinType = Criteria::INNER_JOIN)
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

        $query->useStateQuery()->filterByName($stateNames)->endUse();
        $query->filterBySku($sku);

        return $query;
    }

    /**
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

        $query->useStateQuery()->filterByName($stateNames)->endUse();
        $query->filterBySku($sku);

        return $query;
    }

    /**
     * @param array $orderItemIds
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrderItems(array $orderItemIds)
    {
        return $this->getSalesQueryContainer()->querySalesOrderItem()
            ->filterByIdSalesOrderItem($orderItemIds);
    }

    /**
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
     * @param array|string[] $activeProcesses
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery
     */
    public function getActiveProcesses(array $activeProcesses)
    {
        $query = $this->getFactory()->createOmsOrderProcessQuery();

        return $query->filterByName($activeProcesses);
    }

    /**
     * @param array $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function getOrderItemStates(array $orderItemStates)
    {
        $query = $this->getFactory()->createOmsOrderItemStateQuery();

        return $query->filterByIdOmsOrderItemState($orderItemStates);
    }

    /**
     * @param array $processIds
     * @param array $stateBlacklist
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function queryMatrixOrderItems(array $processIds, array $stateBlacklist)
    {
        $query = $this->getFactory()->createSalesOrderItemQuery();
        $query->filterByFkOmsOrderProcess($processIds);
        if ($stateBlacklist) {
            $query->filterByFkOmsOrderItemState($stateBlacklist, Criteria::NOT_IN);
        }

        return $query;
    }

    /**
     * @param string[] $orderItemStates
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery
     */
    public function querySalesOrderItemStatesByName(array $orderItemStates)
    {
        $query = $this->getFactory()->createOmsOrderItemStateQuery();
        $query->filterByName($orderItemStates);

        return $query;
    }

}
