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
     * @param OrderInterface $orderTransfer
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $orderTransfer, CheckoutRequestInterface $request);

}
