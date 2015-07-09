<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CustomerCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class CustomerCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param OrderTransfer $order
     * @param CheckoutRequestTransfer $request
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $this->getDependencyContainer()->createCustomerOrderHydrator()->hydrateOrderTransfer($order, $request);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createCustomerOrderSaver()->saveOrder($orderTransfer, $checkoutResponse);
    }

    public function checkPreconditions(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->createPreconditionChecker()->checkPreconditions($checkoutRequest, $checkoutResponse);
    }

}
