<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Sales\Business\SalesBusinessFactory getFactory()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * Specification:
     * - Add username to comment // TODO FW This is unexpected
     * - Save comment to database
     *
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
     * Specification:
     * - Return an associative array:
     *     Keys are sales-order ids.
     *     Values are all manual events which are triggerable at the current state.
     *
     * TODO This method shouldn't be here, because it exposes that there is something like "manual event". Move it to OMS.
     * TODO FW The name is too verbose. Why not: getManualEventsBySalesOrderId()
     *
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
     * TODO FW Is this method needed? In any case the name is wrong.
     *
     * @param int $idOrder // TODO FW Rename to $idSalesOrder
     *
     * @return array
     */
    public function getAggregateState($idOrder)
    {
        $orderManager = $this->getFactory()->createOrderDetailsManager();

        return $orderManager->getAggregateState($idOrder);
    }

    /**
     * Specification:
     * - Return the distinct states of all order items for the given order id
     *
     * TODO FW The name is not good. Why not: getDistinctStatesBySalesOrderId(). Create a new method and deprecate the existing.
     *
     * @param int $idOrder // TODO FW Rename to $idSalesOrder
     *
     * @return array
     */
    public function getUniqueOrderStates($idOrder)
    {
        $orderManager = $this->getFactory()->createOrderDetailsManager();

        return $orderManager->getUniqueOrderStates($idOrder);
    }

    /**
     * Specification:
     * - Return all comments for the given order id
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getOrderCommentsByOrderId($idSalesOrder)
    {
        $commentManager = $this->getFactory()->createCommentsManager();

        return $commentManager->getCommentsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @deprecated
     *
     * TODO FW Remove in major release
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
     * TODO FW This method is just a proxy for the query container without any business logig. Therefore it shouldn't exist. Please deprecated and remove later.
     *
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
     * Specification:
     * - Save order and items to database
     * - Set "is test" flag
     * - Set order transfer to response
     *
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
     * TODO FW Move to own bundle
     *
     * Splits sales order items which have a quantity > 1 into two parts. One part with the new given quantity and
     * the other part with the rest.
     *
     * Example:
     *   Item A with quantity = 100
     * Split(20)
     *   Item A with quantity = 80
     *   New Item B with quantity = 20
     *
     * Specification:
     * - Validate if split is possible. (Otherwise return $response->getSuccess() === false and add validation messages)
     * - Create a copy of the given order item with given quantity
     * - Decrement the quantity of the original given order item (including all options)
     * - Return $response->getSuccess() === true
     *
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
     * TODO FW What is this method doing? Does it belong here?
     *
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
     * TODO FW This method is just updating the order. Nothing to do with the customer... Please remove.
     *
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
     * Specification:
     * - Replaces all values of the order address by the values from the addresses transfer
     * - Return the updated entity
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressesTransfer
     * @param int $idAddress
     *
     * @return mixed // TODO The entity is returned
     */
    public function updateOrderAddress(AddressTransfer $addressesTransfer, $idAddress)
    {
        return $this->getFactory()
            ->createOrderDetailsManager()
            ->updateOrderAddress($addressesTransfer, $idAddress);
    }

    /**
     * TODO FW This needs to be discussed. The sales-bunde should not know anything about payment logs.
     *
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
     * Returns a list of of orders for the given customer id and (optional) filters.
     *
     * TODO FW This method uses the same transfer as a parameter and return object
     * TODO FW Hidden required field inside of the transfer. The customer id must be set as a another parameter.
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer Must have getIdCustomer() !== null
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
     * TODO FW This method returns strange things, which are need for a specific GUI probably. What the heck are "order details"... Split it into smaller parts and collect the data in the gateway controller.
     * TODO FW The name is misleading. It would be ok for getOrderDetails($idSalesOrder), but in this case it enriches the given $orderTransfer. I would prefer to not have an in/out parameter
     *
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
     * TODO FW This belongs to refund bundle. Remove from here.
     *
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
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseAmounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createExpenseOrderTotalAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderGrandTotal(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createGrandTotalOrderTotalAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemAmounts(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemOrderOrderAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotal(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createSubtotalOrderAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemTaxAmount(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderItemTaxAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderTaxAmountAggregator(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderTaxAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderExpenseTaxAmountAggregator(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createOrderExpenseTaxAmountAggregator()->aggregate($orderTransfer);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByIdSalesOrder($idSalesOrder);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function getOrderItemTotalsByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * TODO FW Move to own bundle and add description
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTotalByOrderTransfer(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createOrderTotalsAggregator()->aggregateByOrderTransfer($orderTransfer);
    }

}
