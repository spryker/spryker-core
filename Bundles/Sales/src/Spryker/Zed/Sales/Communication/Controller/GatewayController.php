<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrdersAction(OrderListTransfer $orderListTransfer)
    {
        return $this->getFacade()->getCustomerOrders($orderListTransfer, $orderListTransfer->getIdCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetailsAction(OrderTransfer $orderTransfer)
    {
        try {
            $orderTransfer = $this->getFacade()
                ->getCustomerOrder($orderTransfer);
        } catch (InvalidSalesOrderException $e) {
            $orderTransfer = new OrderTransfer();
            $this->setSuccess(false);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedOrdersAction(OrderListTransfer $orderListTransfer)
    {
        return $this->getFacade()
            ->getPaginatedCustomerOrders(
                $orderListTransfer,
                $orderListTransfer->getIdCustomer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderListAction(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer
    {
        return $this->getFacade()->getOffsetPaginatedCustomerOrderList($orderListRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrdersOverviewAction(OrderListTransfer $orderListTransfer)
    {
        return $this->getFacade()
            ->getPaginatedCustomerOrdersOverview(
                $orderListTransfer,
                $orderListTransfer->getIdCustomer()
            );
    }

    /**
     * @deprecated Security issue with missing customer id constraint, use getOrderDetailsAction() instead.
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderByIdSalesOrderAction($idSalesOrder)
    {
        return $this->getFacade()->getOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrderByOrderReferenceAction(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFacade()->getCustomerOrderByOrderReference($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItemsAction(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer
    {
        return $this->getFacade()->getOrderItems($orderItemFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrdersAction(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        return $this->getFacade()->searchOrders($orderListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrderAction(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        return $this->getFacade()->cancelOrder($orderCancelRequestTransfer);
    }
}
