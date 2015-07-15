<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Shared\Cart\Messages\Messages;
use Generated\Shared\Cart\CartInterface;

class RemoveOperator extends AbstractOperator
{

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    protected function changeCart(CartInterface $cart, ChangeInterface $change)
    {
        return $this->storageProvider->removeItems($cart, $change);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::REMOVE_ITEMS_SUCCESS;
    }

}
