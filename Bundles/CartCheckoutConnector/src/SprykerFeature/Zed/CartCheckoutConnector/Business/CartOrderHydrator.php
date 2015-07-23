<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CartCheckoutConnector\Business;

use Generated\Shared\CartCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CartCheckoutConnector\OrderInterface;
use Generated\Shared\CartCheckoutConnector\OrderItemInterface;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\OrderItemTransfer;

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
     * @param \ArrayObject|CartItemTransfer[] $cartItems
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
                $orderItems[] = $this->createOrderItemTransfer($cartItem);
            }
        }

        return new \ArrayObject($orderItems);
    }

    /**
     * @param CartItemTransfer $cartItem
     *
     * @return array
     */
    protected function expandCartItem(CartItemTransfer $cartItem)
    {
        $result = [];
        for ($i = 1; $i <= $cartItem->getQuantity(); $i++) {
            $result[] = $this->createOrderItemTransfer($cartItem);
        }
        return $result;
    }

    /**
     * @return OrderItemInterface
     */
    protected function getOrderItemTransfer()
    {
        return new OrderItemTransfer();
    }

    /**
     * @param CartItemTransfer $cartItem
     *
     * @return OrderItemInterface
     */
    protected function createOrderItemTransfer(CartItemTransfer $cartItem)
    {
        $orderItem = $this->getOrderItemTransfer();
        $orderItem->setGrossPrice($cartItem->getGrossPrice());
        $orderItem->setQuantity(1);
        $orderItem->setPriceToPay($cartItem->getPriceToPay());
        $orderItem->setSku($cartItem->getSku());
        $orderItem->setName($cartItem->getName());

        return $orderItem;
    }

}
