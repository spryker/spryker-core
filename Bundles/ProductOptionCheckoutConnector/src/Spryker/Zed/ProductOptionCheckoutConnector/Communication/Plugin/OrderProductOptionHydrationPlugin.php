<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;

/**
 * @method \Spryker\Zed\ProductOptionCheckoutConnector\Business\ProductOptionCheckoutConnectorFacade getFacade()
 * @method \Spryker\Zed\ProductOptionCheckoutConnector\Communication\ProductOptionCheckoutConnectorCommunicationFactory getFactory()
 */
class OrderProductOptionHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        $this->getFacade()->hydrateOrderTransfer($orderTransfer, $checkoutRequest);
    }

}
