<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Sales\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface SalesOrderSaverInterface
{

    /**
     * @param OrderInterface $order
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderInterface $order, CheckoutResponseTransfer $checkoutResponse);

}
