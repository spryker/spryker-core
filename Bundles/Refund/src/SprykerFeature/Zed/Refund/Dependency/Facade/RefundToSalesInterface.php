<?php

namespace SprykerFeature\Zed\Refund\Dependency\Facade;

use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;

interface RefundToSalesInterface
{

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity);

    /**
     * @param int $idRefund
     * @param OrderItemsAndExpensesTransfer $orderItemsAndExpenses
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpenses);

}
