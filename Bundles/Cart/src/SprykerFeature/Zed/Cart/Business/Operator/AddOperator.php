<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Shared\Cart\Messages\Messages;
use Generated\Shared\Cart\CartInterface;

class AddOperator extends AbstractOperator
{

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    protected function changeCart(CartInterface $cart, ChangeInterface $change)
    {
        $cart = $this->storageProvider->addItems($cart, $change);

        return $this->groupCartItems($cart);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::ADD_ITEMS_SUCCESS;
    }

}
