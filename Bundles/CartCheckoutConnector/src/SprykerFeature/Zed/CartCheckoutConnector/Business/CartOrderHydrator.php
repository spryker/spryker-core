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

        $orderItems = $this->transformCartItemsToOrderItems($cart->getItems());
        $order->setItems($orderItems);

        $order
            ->setTotals($cart->getTotals())
            ->setDiscounts($cart->getDiscounts())
            ->setExpenses($cart->getExpenses())
        ;
    }

    /**
     * @param \ArrayObject|CartItemTransfer[] $cartItems
     *
     * @return \ArrayObject
     */
    protected function transformCartItemsToOrderItems(\ArrayObject $cartItems)
    {
        $result = [];

        foreach ($cartItems as $cartItem) {
            $orderItem = $this->getOrderItemTransfer();
            $orderItem->setGrossPrice($cartItem->getGrossPrice());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setPriceToPay($cartItem->getPriceToPay());
            $orderItem->setSku($cartItem->getSku());
            $orderItem->setName($cartItem->getName());
            $result[] = $orderItem;
        }

        return new \ArrayObject($result);
    }

    /**
     * @return OrderItemInterface
     */
    protected function getOrderItemTransfer()
    {
        return new OrderItemTransfer();
    }

}
