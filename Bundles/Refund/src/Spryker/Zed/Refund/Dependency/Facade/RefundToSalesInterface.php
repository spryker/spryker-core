<?php

namespace Spryker\Zed\Refund\Dependency\Facade;

use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;

interface RefundToSalesInterface
{

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity);

    /**
     * @param int $idRefund
     * @param \Generated\Shared\Transfer\OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer);

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder);

}
