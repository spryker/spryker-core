<?php

namespace Spryker\Zed\Refund\Dependency\Facade;

use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

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
     * @param OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer);

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);

}
