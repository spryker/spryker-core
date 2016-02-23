<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class ProductOptionOrderHydrator implements ProductOptionOrderHydratorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $order
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     *
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $order, CheckoutRequestTransfer $request)
    {
        $cart = $request->getCart();

        $this->transferProductOptionsFromCartToOrder($cart->getItems(), $order->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return void
     */
    private function transferProductOptionsFromCartToOrder(\ArrayObject $cartItems, \ArrayObject $orderItems)
    {
        foreach ($cartItems as $cartItem) {
            $this->transferItemOptions($cartItem, $orderItems);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return void
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
