<?php

/**
 * Created by PhpStorm.
 * User: wesner
 * Date: 30.06.15
 * Time: 10:52
 */

namespace Spryker\Zed\Oms\Persistence;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLogQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use DateTime;

interface OmsQueryContainerInterface
{

    /**
     * @param array $states
     * @param string $processName
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByState(array $states, $processName);

    /**
     * @param $idOrder
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdOrder($idOrder);

    /**
     * @param SpySalesOrder $order
     *
     * @return SpyOmsTransitionLogQuery
     */
    public function queryLogForOrder(SpySalesOrder $order);

    /**
     * @param int $idOrder
     * @param bool $orderById
     *
     * @return SpyOmsTransitionLogQuery
     */
    public function queryLogByIdOrder($idOrder, $orderById = true);

    /**
     * @param DateTime $now
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithExpiredTimeouts(DateTime $now);

    /**
     * @param StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return SpySalesOrderItemQuery
     */
    public function countSalesOrderItemsForSku(array $states, $sku, $returnTest = true);

    /**
     * @param StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsForSku(array $states, $sku, $returnTest = true);

    /**
     * @param array $orderItemIds
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItems(array $orderItemIds);

    /**
     * @param int $idOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder);

    /**
     * @param array|string[] $activeProcesses
     *
     * @return SpyOmsOrderProcessQuery
     */
    public function getActiveProcesses(array $activeProcesses);

    /**
     * @param array $orderItemStates
     *
     * @return SpyOmsOrderItemStateQuery
     */
    public function getOrderItemStates(array $orderItemStates);

    /**
     * @param array $processIds
     * @param array $stateBlacklist
     *
     * @return SpySalesOrderItemQuery
     */
    public function queryMatrixOrderItems(array $processIds, array $stateBlacklist);

    /**
     * @param string[] $orderItemStates
     *
     * @return SpyOmsOrderItemStateQuery
     */
    public function querySalesOrderItemStatesByName(array $orderItemStates);

}
