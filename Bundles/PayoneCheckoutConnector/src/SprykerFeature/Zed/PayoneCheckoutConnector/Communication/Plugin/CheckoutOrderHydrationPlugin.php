<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin;

use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;

class CheckoutOrderHydrationPlugin implements CheckoutOrderHydrationInterface{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {

    }

}
