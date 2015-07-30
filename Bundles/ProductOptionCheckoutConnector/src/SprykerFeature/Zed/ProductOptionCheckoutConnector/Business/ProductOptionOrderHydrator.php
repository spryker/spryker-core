<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\ProductOptionCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\ProductOptionCheckoutConnector\OrderInterface;
use Generated\Shared\ProductOptionCheckoutConnector\OrderItemInterface;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;

class ProductOptionOrderHydrator implements ProductOptionOrderHydratorInterface
{
    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $cart = $request->getCart();

        // ... copy ProductOption DTOs from CartItems, to OrderItems, which should alread have been hydrated
        // by the CartCheckoutConnector plugin
    }

}
