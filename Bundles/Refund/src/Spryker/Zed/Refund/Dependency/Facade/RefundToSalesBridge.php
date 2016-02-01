<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund\Dependency\Facade;

use Spryker\Zed\Sales\Business\SalesFacade;
use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class RefundToSalesBridge implements RefundToSalesInterface
{

    /**
     * @var SalesFacade
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacade $salesFacade
     */
    public function __construct($salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->salesFacade->splitSalesOrderItem($idSalesOrderItem, $quantity);
    }

    /**
     * @param int $idRefund
     * @param \Generated\Shared\Transfer\OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     *
     * @return void
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer)
    {
        $this->salesFacade->updateOrderItemsAndExpensesAfterRefund($idRefund, $orderItemsAndExpensesTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
    }

}
