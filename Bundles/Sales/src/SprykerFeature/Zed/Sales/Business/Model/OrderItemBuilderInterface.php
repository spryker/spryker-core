<?php
namespace SprykerFeature\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Generated\Shared\Transfer\SalesOrderTransfer;

interface OrderItemBuilderInterface
{

    /**
     * @param OrderItem $transferItem
     * @param Order $transferOrder
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem
     */
    public function createOrderItemEntity(OrderItem $transferItem, Order $transferOrder);

}
