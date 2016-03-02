<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface FinderInterface
{

    /**
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem);

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlagged($idOrder, $flag);

    /**
     * @param int $idOrder
     * @param string $flag
     *
     * @return bool
     */
    public function isOrderFlaggedAll($idOrder, $flag);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function getReservedOrderItemsForSku($sku);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return array
     */
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag);

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag);

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface[]
     */
    public function getProcesses();

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return string
     */
    public function getStateDisplayName(SpySalesOrderItem $orderItem);

    /**
     * @param int $idSalesOrder
     *
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface[]
     */
    public function getManualEventsByIdSalesOrder($idSalesOrder);

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getDistinctManualEventsByIdSalesOrder($idSalesOrder);

}
