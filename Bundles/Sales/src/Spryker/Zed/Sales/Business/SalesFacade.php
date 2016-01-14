<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method \Spryker\Zed\Sales\Business\SalesBusinessFactory getFactory()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        $commentsManager = $this->getFactory()->createCommentsManager();

        return $commentsManager->saveComment($commentTransfer);
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getArrayWithManualEvents($idOrder)
    {
        $orderManager = $this->getFactory()->createOrderDetailsManager();

        return $orderManager->getArrayWithManualEvents($idOrder);
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getAggregateState($idOrder)
    {
        $orderManager = $this->getFactory()->createOrderDetailsManager();

        return $orderManager->getAggregateState($idOrder);
    }

    /**
     * @deprecated
     *
     * @param int $idOrderItem
     *
     * @return array
     */
    public function getOrderItemManualEvents($idOrderItem)
    {
        return $this->getFactory()->getFacadeOms()->getManualEvents($idOrderItem);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderManager()
            ->getOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        return $this->getFactory()
            ->createOrderManager()
            ->saveOrder($quoteTransfer, $checkoutResponseTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->getFactory()->createOrderItemSplitter()->split($idSalesOrderItem, $quantity);
    }

    /**
     * @param int $idRefund
     * @param \Generated\Shared\Transfer\OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     *
     * @return void
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer)
    {
        $this->getFactory()
            ->createOrderDetailsManager()
            ->updateOrderItemsAndExpensesAfterRefund($idRefund, $orderItemsAndExpensesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function updateOrderCustomer(OrderTransfer $orderTransfer, $idOrder)
    {
        return $this->getFactory()
            ->createOrderDetailsManager()
            ->updateOrderCustomer($orderTransfer, $idOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressesTransfer
     * @param int $idAddress
     *
     * @return mixed
     */
    public function updateOrderAddress(AddressTransfer $addressesTransfer, $idAddress)
    {
        return $this->getFactory()
            ->createOrderDetailsManager()
            ->updateOrderAddress($addressesTransfer, $idAddress);
    }

    /**
     * @param string $idOrder
     *
     * @return array
     */
    public function getPaymentLogs($idOrder)
    {
        return $this->getFactory()
            ->createOrderDetailsManager()
            ->getPaymentLogs($idOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        return $this->getFactory()
            ->createOrderManager()
            ->getOrders($orderListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createOrderDetailsManager()
            ->getOrderDetails($orderTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    public function getRefunds($idSalesOrder)
    {
        return $this->getFactory()->getFacadeRefund()
            ->getRefundsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getTaxAmountByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getTaxAmountByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return CalculatedDiscountTransfer[]
     */
    public function getDiscountsByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getDiscountsByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getItemTotalAmountByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getItemTotalAmountByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getGrandTotalByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getGrandTotalByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getSubtotalByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getSubtotalByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return int
     */
    public function getSubtotalByIdSalesOrderWithExpenses($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getSubtotalByIdSalesOrderWithExpenses($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return CalculatedDiscountTransfer[]
     */
    public function getDiscountTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getDiscountTotalsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return CalculatedDiscountTransfer[]
     */
    public function getDiscountTotalAmountByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getDiscountTotalAmountByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getExpensesTotalAmountByIdSalesOrder($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getExpensesTotalAmountByIdSalesOrder($idSalesOrderItem);
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return int
     */
    public function getItemTotalAmountByIdSalesOrderItemAfterDiscounts($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->getItemTotalAmountByIdSalesOrderItemAfterDiscounts($idSalesOrderItem);
    }

}
