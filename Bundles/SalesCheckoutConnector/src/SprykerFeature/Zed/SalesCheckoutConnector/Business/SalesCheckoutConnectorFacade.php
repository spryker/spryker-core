<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SalesCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class SalesCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->getSalesOrderSaver()->saveOrder($orderTransfer, $checkoutResponse);
    }

}
