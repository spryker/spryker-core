<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Shared\Cart\Messages\Messages;
use Generated\Shared\Cart\CartInterface;

class DecreaseOperator extends AbstractOperator
{

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    protected function changeCart(CartInterface $cart, ChangeInterface $change)
    {
        return $this->storageProvider->decreaseItems($cart, $change);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::DECREASE_ITEMS_SUCCESS;
    }

}
