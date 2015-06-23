<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use SprykerFeature\Shared\Cart\Messages\Messages;
use Generated\Shared\Transfer\CartCartInterfaceTransfer;
use Generated\Shared\Transfer\CartItemCollectionInterfaceTransfer;

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
