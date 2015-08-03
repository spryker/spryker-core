<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\ProductOptionCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\ProductOptionCheckoutConnector\OrderInterface;

interface ProductOptionOrderHydratorInterface
{

    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request);

}
