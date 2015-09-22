<?php

namespace SprykerFeature\Zed\Refund\Dependency\Facade;

use Generated\Shared\Sales\ItemSplitResponseInterface;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;

interface RefundToSalesInterface
{

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return ItemSplitResponseInterface
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity);

    /**
     * @param int $idRefund
     * @param OrderItemsAndExpensesTransfer $orderItemsAndExpenses
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpenses);

}
