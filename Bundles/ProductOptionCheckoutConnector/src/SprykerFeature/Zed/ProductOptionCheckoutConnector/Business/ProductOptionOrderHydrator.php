<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\ProductOptionCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\ProductOptionCheckoutConnector\CartItemInterface;
use Generated\Shared\ProductOptionCheckoutConnector\OrderInterface;
use Generated\Shared\ProductOptionCheckoutConnector\OrderItemInterface;

class ProductOptionOrderHydrator implements ProductOptionOrderHydratorInterface
{
    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $cart = $request->getCart();

        $this->transferProductOptionsFromCartToOrder($cart->getItems(), $order->getItems());
    }

    /**
     * @param \ArrayObject|CartItemInterface[] $cartItems
     * @param \ArrayObject|OrderItemInterface[] $orderItems
     */
    private function transferProductOptionsFromCartToOrder(\ArrayObject $cartItems, \ArrayObject $orderItems)
    {
        foreach ($cartItems as $cartItem) {
            $this->transferItemOptions($cartItem, $orderItems);
        }
    }

    /**
     * @param CartItemInterface $cartItem
     * @param \ArrayObject|OrderItemInterface[] $orderItems
     */
    private function transferItemOptions(CartItemInterface $cartItem, \ArrayObject $orderItems)
    {
        foreach ($orderItems as $orderItem) {

            if ($cartItem->getSku() !== $orderItem->getSku()) {
                continue;
            }

            if (empty($cartItem->getProductOptions())) {
                continue;
            }

            $orderItem->setProductOptions(clone $cartItem->getProductOptions());
        }
    }

}
