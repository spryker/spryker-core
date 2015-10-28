<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

use SprykerFeature\Zed\Oms\Business\Process\ProcessInterface;
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
     * @return SpySalesOrderItem
     */
    public function getReservedOrderItemsForSku($sku);

    /**
     * @param string $sku
     *
     * @return SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku);

    /**
     * @param SpySalesOrder $order
     *
     * @return array
     */
    public function getGroupedManuallyExecutableEvents(SpySalesOrder $order);

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag);

    /**
     * @param SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithoutFlag(SpySalesOrder $order, $flag);

    /**
     * @return ProcessInterface[]
     */
    public function getProcesses();

    /**
     * @param SpySalesOrderItem $orderItem
     *
     * @return string
     */
    public function getStateDisplayName(SpySalesOrderItem $orderItem);

}
