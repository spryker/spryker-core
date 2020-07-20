<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Sales;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesClientInterface
{
    /**
     * Specification:
     * - Returns the sales orders for the given customer and filters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer);

    /**
     * Specification:
     * - Makes Zed request.
     * - Returns the sales orders for the given customer and filters.
     * - OrderListTransfer::$filters can contain offset-based pagination and ordering parameters.
     * - OrderListTransfer::$pagination can be used to apply page-based pagination strategy to the queried orders.
     * - Hydrates the resulting orders with related data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedOrder(OrderListTransfer $orderListTransfer);

    /**
     * Specification:
     * - Makes Zed request.
     * - Returns a transfer with the filtered list of orders for the given customer.
     * - Uses OrderListRequestTransfer::$filter to pull params for offset-based pagination strategy.
     * - OrderListRequestTransfer::customerReference must be set.
     * - Updates the total number of orders for the customer to the pagination transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderList(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer;

    /**
     * Specification:
     *  - Returns a list of of orders for the given customer id and (optional) filters, without order items information.
     *  - Aggregates order totals calls -> SalesAggregator
     *  - Paginates order list for limited result
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
     * - Returns details for the given order id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Returns the order for the given order reference.
     * - OrderTransfer should have the customerReference and orderReference set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrderByOrderReference(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves order items from persistence by criteria from OrderItemFilterTransfer.
     * - Executes OrderItemExpanderPluginInterface stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires OrderListTransfer::customerReference to be set.
     * - Requires OrderListTransfer::pagination to be set.
     * - Requires OrderListTransfer::format to be set.
     * - Filters orders by OrderListTransfer::filterFields if provided.
     * - Filters orders by OrderListTransfer::filter if provided.
     * - Executes SearchOrderQueryExpanderPluginInterface plugin stack.
     * - Finds orders by criteria from OrderListTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires OrderCancelRequestTransfer::orderReference to be set.
     * - Requires CustomerTransfer:customerReference to be set.
     * - Requires ItemTransfer::idSalesOrderItem to be set.
     * - Retrieves OrderTransfer filtered by orderReference and customerReference.
     * - Checks OrderTransfer::isCancellable.
     * - Triggers cancel event for provided order items.
     * - Returns "isSuccessful=true" and order transfer on success or `isSuccessful=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer;
}
