<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Controller;

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
    public function getOrderByIdSalesOrder($idSalesOrder)
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
}
