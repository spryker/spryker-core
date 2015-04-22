<?php

namespace SprykerFeature\Zed\Cart\Business\Model\Strategies;

use SprykerFeature\Shared\Sales\Transfer\OrderItem;

class MergeStrategySum implements MergeStrategyInterface
{

    /**
     * @param OrderItem $cartItem
     * @param \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $itemToAdd
     * @return int
     */
    public function getQuantity(OrderItem $cartItem, \SprykerFeature\Zed\Cart\Persistence\Propel\SpyCartItem $itemToAdd)
    {
        return $cartItem->getQuantity() + $itemToAdd->getQuantity();
    }
}
