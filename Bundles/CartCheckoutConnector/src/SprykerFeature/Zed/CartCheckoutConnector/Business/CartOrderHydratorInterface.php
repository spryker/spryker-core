<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Business;

use Generated\Shared\CartCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CartCheckoutConnector\OrderInterface;

interface CartOrderHydratorInterface
{

    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request);

}
