<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\OrderListTransfer;
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
}
