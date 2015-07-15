<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest);

}
