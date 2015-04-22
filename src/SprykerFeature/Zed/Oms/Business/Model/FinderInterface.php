<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

/**
 * Interface FinderInterface
 * @package SprykerFeature\Zed\Oms\Business\Model
 */
interface FinderInterface
{
    /**
     * @param string $sku
     * @return \SprykerFeature_Zed_Library_Propel_ClearAllReferencesIterator
     */
    public function getReservedOrderItemsForSku($sku);

    /**
     * @param string $sku
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array
     */
    public function getGroupedManuallyExecutableEvents(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @param string                                               $flag
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[]
     */
    public function getItemsWithFlag(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order, $flag);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @param string                                               $flag
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[]
     */
    public function getItemsWithoutFlag(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order, $flag);

    /**
     * @return ProcessInterface[]
     */
    public function getProcesses();

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @return string
     */
    public function getStatusDisplayName(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem);
}
