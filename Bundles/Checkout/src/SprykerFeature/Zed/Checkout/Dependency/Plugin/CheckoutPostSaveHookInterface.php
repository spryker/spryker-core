<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Transfer\OrderTransfer;

interface CheckoutPostSaveHookInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     */
    public function executeHook(OrderTransfer $orderTransfer, CheckoutResponseInterface $checkoutResponse);

}
