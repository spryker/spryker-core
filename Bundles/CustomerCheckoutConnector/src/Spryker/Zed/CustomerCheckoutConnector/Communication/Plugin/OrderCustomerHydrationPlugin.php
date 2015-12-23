<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Spryker\Zed\CustomerCheckoutConnector\Business\CustomerCheckoutConnectorFacade;
use Spryker\Zed\CustomerCheckoutConnector\Communication\CustomerCheckoutConnectorCommunicationFactory;

/**
 * @method CustomerCheckoutConnectorFacade getFacade()
 * @method CustomerCheckoutConnectorCommunicationFactory getFactory()
 */
class OrderCustomerHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $this->getFacade()->hydrateOrderTransfer($orderTransfer, $checkoutRequest);
    }

}
