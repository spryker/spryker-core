<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductOptionOrderHydrator implements ProductOptionOrderHydratorInterface
{

    /**
     * @param OrderTransfer $order
     * @param CheckoutRequestTransfer $request
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $cart = $request->getCart();

        $this->transferProductOptionsFromCartToOrder($cart->getItems(), $order->getItems());
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $cartItems
     * @param \ArrayObject|ItemTransfer[] $orderItems
     */
    private function transferProductOptionsFromCartToOrder(\ArrayObject $cartItems, \ArrayObject $orderItems)
    {
        foreach ($cartItems as $cartItem) {
            $this->transferItemOptions($cartItem, $orderItems);
        }
    }

    /**
     * @param ItemTransfer $cartItem
     * @param \ArrayObject|ItemTransfer[] $orderItems
     */
    private function transferItemOptions(ItemTransfer $cartItem, \ArrayObject $orderItems)
    {
        foreach ($orderItems as $orderItem) {
            if ($cartItem->getSku() !== $orderItem->getSku()) {
                continue;
            }

            if (empty($cartItem->getProductOptions())) {
                continue;
            }

            $orderItem->setProductOptions(new \ArrayObject());
            foreach ($cartItem->getProductOptions() as $productOptionTransfer) {
                $orderItem->addProductOption(clone $productOptionTransfer);
            }
        }
    }

}
