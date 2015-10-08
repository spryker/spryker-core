<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Checkout\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CheckoutPostSaveHookInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function executeHook(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse);

}
