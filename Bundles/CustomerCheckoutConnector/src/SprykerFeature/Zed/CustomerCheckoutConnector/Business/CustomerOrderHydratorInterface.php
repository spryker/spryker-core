<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CustomerCheckoutConnector\OrderInterface;

interface CustomerOrderHydratorInterface
{

    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request);

}
