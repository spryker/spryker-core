<?php

namespace SprykerFeature\Zed\Cart2\Business\Operator;

use SprykerFeature\Shared\Cart2\Messages\Messages;
use Generated\Shared\Transfer\Cart2CartInterfaceTransfer;
use Generated\Shared\Transfer\Cart2ItemCollectionInterfaceTransfer;

class RemoveOperator extends AbstractOperator
{
    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $changedItems
     *
     * @return CartInterface
     */
    protected function changeCart(CartInterface $cart, ItemCollectionInterface $changedItems)
    {
        return $this->storageProvider->removeItems($cart, $changedItems);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::REMOVE_ITEMS_SUCCESS;
    }
}
