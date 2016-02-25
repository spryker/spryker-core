<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
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
     * - Add username to comment
     * - Save comment to database
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        return $this->getFactory()
            ->createOrderCommentSaver()
            ->save($commentTransfer);
    }

    /**
     * Specification:
     * - Return the distinct states of all order items for the given order id
     *
     * @param int $idSalesOrder
     *
     * @return array|string[]
     */
    public function getDistinctOrderStates($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderDetailsManager()
            ->getDistinctOrderStates($idSalesOrder);
    }

    /**
     * Specification:
     * - Return all comments for the given order id
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getOrderCommentsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createOrderCommentReader()
            ->getCommentsByIdSalesOrder($idSalesOrder);
    }

    /**
     * Specification:
     * - Save order and items to database
     * - Set "is test" flag
     * - update checkout response with saved order data
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $this->getFactory()
            ->createOrderSaver()
            ->saveOrder($quoteTransfer, $checkoutResponseTransfer);
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
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer, $idCustomer)
    {
        return $this->getFactory()
            ->createCustomerOrderReader()
            ->getOrders($orderListTransfer, $idCustomer);
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
}
