<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Sales\Business\SalesFacade;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method SalesFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param OrderListTransfer $orderListTransfer
     * 
     * @return OrderListTransfer
     */
    public function getOrdersAction(OrderListTransfer $orderListTransfer)
    {
        return $this->getFacade()->getOrders($orderListTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetailsAction(OrderTransfer $orderTransfer)
    {
        return $this->getFacade()->getOrderDetails($orderTransfer);
    }

}
