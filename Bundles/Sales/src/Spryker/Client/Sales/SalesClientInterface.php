<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Sales;

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
     * - Returns the sales orders for the given customer and filters.
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
     *  - Returns a list of of orders for the given customer id and (optional) filters, without order items information.
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
     * - Returns details for the given order id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer);
}
