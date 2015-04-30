<?php

namespace SprykerFeature\Zed\Cart\Business\Operator;

use SprykerFeature\Shared\Cart\Messages\Messages;
use SprykerFeature\Shared\Cart\Transfer\CartInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;

class AddOperator extends AbstractOperator
{
    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $changedItems
     *
     * @return CartInterface
     */
    protected function changeCart(CartInterface $cart, ItemCollectionInterface $changedItems)
    {
        return $this->storageProvider->addItems($cart, $changedItems);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::ADD_ITEMS_SUCCESS;
    }
}