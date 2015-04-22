<?php

namespace SprykerFeature\Zed\Cart\Business\Model\Strategies;

use SprykerFeature\Shared\Sales\Transfer\OrderItem;

class MergeStrategyMax implements MergeStrategyInterface
{

    /**
     * @param OrderItem $cartItem
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $itemToAdd
     * @return int
     */
    public function getQuantity(OrderItem $cartItem, \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $itemToAdd)
    {
        $cartItemQty = $cartItem->getQuantity();
        $itemToAddQty = $itemToAdd->getQuantity();

        return max($cartItemQty, $itemToAddQty);
    }
}
