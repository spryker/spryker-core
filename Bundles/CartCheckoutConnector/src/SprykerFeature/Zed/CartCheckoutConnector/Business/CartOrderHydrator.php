<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Business;

use Generated\Shared\CartCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CartCheckoutConnector\OrderInterface;
use Generated\Shared\CartCheckoutConnector\ItemInterface;
use Generated\Shared\Transfer\ItemTransfer;

class CartOrderHydrator implements CartOrderHydratorInterface
{
    /**
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $cart = $request->getCart();

        $order->setItems($this->transformCartItemsToOrderItems($cart->getItems()))
            ->setTotals($cart->getTotals())
            ->setDiscounts($cart->getDiscounts())
            ->setExpenses($cart->getExpenses());
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $cartItems
     *
     * @return \ArrayObject
     */
    protected function transformCartItemsToOrderItems(\ArrayObject $cartItems)
    {
        $orderItems = [];
        foreach ($cartItems as $cartItem) {
            if ($cartItem->getQuantity() > 1) {
                $orderItems = array_merge($orderItems, $this->expandCartItem($cartItem));
            } else {
                $orderItems[] = $this->createItemTransfer($cartItem);
            }
        }

        return new \ArrayObject($orderItems);
    }

    /**
     * @param ItemTransfer $cartItem
     *
     * @return array
     */
    protected function expandCartItem(ItemTransfer $cartItem)
    {
        $result = [];
        for ($i = 1; $i <= $cartItem->getQuantity(); $i++) {
            $result[] = $this->createItemTransfer($cartItem);
        }
        return $result;
    }

    /**
     * @return ItemInterface
     */
    protected function getItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @param ItemInterface $cartItem
     *
     * @return ItemInterface
     */
    protected function createItemTransfer(ItemInterface $cartItem)
    {
        $orderItem = $this->getItemTransfer();
        $orderItem->setGrossPrice($cartItem->getGrossPrice());
        $orderItem->setQuantity(1);
        $orderItem->setPriceToPay($cartItem->getPriceToPay());
        $orderItem->setSku($cartItem->getSku());
        $orderItem->setName($cartItem->getName());
        $orderItem->setTaxSet($cartItem->getTaxSet());

        return $orderItem;
    }

}
