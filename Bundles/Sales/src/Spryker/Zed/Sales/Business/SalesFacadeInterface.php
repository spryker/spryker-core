<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Sales\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method \Spryker\Zed\Sales\Business\SalesBusinessFactory getFactory()
 */
interface SalesFacadeInterface
{
    /**
     * Specification:
     * - Adds username to comment
     * - Saves comment to database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer);

    /**
     * Specification:
     * - Returns the distinct states of all order items for the given order id
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctOrderStates($idSalesOrder);

    /**
     * Specification:
     * - Returns all comments for the given order id
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderDetailsCommentsTransfer
     */
    public function getOrderCommentsByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     * - Saves order and items to database
     * - Sets "is test" flag
     * - Updates checkout response with saved order data
     * - Sets initial state for state machine
     *
     * @api
     *
     * @deprecated Use saveSalesOrder() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer);

    /**
     * Specification:
     * - Saves order and items to database
     * - Sets "is test" flag
     * - Updates checkout response with saved order data
     * - Sets initial state for state machine
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveSalesOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     * - Update sales order with data from order transfer
     * - Returns true if order was successfully updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrder
     *
     * @return bool
     */
    public function updateOrder(OrderTransfer $orderTransfer, $idSalesOrder);

    /**
     * Specification:
     * - Replaces all values of the order address by the values from the addresses transfer
     * - Returns true if order was successfully updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressesTransfer
     * @param int $idAddress
     *
     * @return boolean
     */
    public function updateOrderAddress(AddressTransfer $addressesTransfer, $idAddress);

    /**
     * Returns a list of orders for the given customer id and (optional) filters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getCustomerOrders(OrderListTransfer $orderListTransfer, $idCustomer);

    /**
     * Specification:
     *  - Returns a list of orders for the given customer id and (optional) filters.
     *  - Aggregates order totals calls -> SalesAggregator
     *  - Paginates order list for limited result
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrders(OrderListTransfer $orderListTransfer, $idCustomer);

    /**
     * Specification:
     *  - Returns a list of orders for the given customer id and (optional) filters, without order items information.
     *  - Aggregates order totals calls -> SalesAggregator.
     *  - Paginates order list for limited result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrdersOverview(OrderListTransfer $orderListTransfer): OrderListTransfer;

    /**
     * Specification:
     *  - Returns the order for the given customer id and sales order id.
     *  - Aggregates order totals calls -> SalesAggregator
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrder(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Returns the order for the given sales oder id.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     * - Returns the order for the given sales order item id.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrderItem($idSalesOrderItem);

    /**
     *
     * Specification:
     *  - Expands order by quantity 1 recalculates order transfer with new values
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer|null $checkoutResponseTransfer Deprecated: Parameter is not used
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandSalesOrder(QuoteTransfer $quoteTransfer, ?CheckoutResponseTransfer $checkoutResponseTransfer = null);
}
