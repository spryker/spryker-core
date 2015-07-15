<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface CustomerOrderSaverInterface
{

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse);

}
