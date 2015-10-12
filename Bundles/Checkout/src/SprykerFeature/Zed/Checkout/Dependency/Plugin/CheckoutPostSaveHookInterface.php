<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Dependency\Plugin;

use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Checkout\OrderInterface;

interface CheckoutPostSaveHookInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseInterface $checkoutResponse
     */
    public function executeHook(OrderInterface $orderTransfer, CheckoutResponseInterface $checkoutResponse);

}
