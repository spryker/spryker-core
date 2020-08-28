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
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Sales\SalesFactory getFactory()
 * @method \Spryker\Client\Sales\SalesConfig getConfig()
 */
class SalesClient extends AbstractClient implements SalesClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getOrders($orderListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedOrder(OrderListTransfer $orderListTransfer)
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getPaginatedOrders($orderListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderList(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrdersOverview(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getPaginatedCustomerOrdersOverview($orderListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getOrderDetails($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrderByOrderReference(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getCustomerOrderByOrderReference($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer
    {
        return $this->getFactory()
            ->createZedSalesStub()
            ->getOrderItems($orderItemFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->requireCustomerReference();

        return $this->getFactory()
            ->createZedSalesStub()
            ->searchOrders($orderListTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        $orderCancelRequestTransfer
            ->requireCustomer()
            ->getCustomer()
                ->requireCustomerReference();

        return $this->getFactory()
            ->createZedSalesStub()
            ->cancelOrder($orderCancelRequestTransfer);
    }
}
