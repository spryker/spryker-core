<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Shared\Cart\Messages\Messages;
use Generated\Shared\Transfer\CartTransfer;

class DecreaseOperator extends AbstractOperator
{

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return CartTransfer
     */
    protected function changeCart(CartTransfer $cart, ChangeTransfer $change)
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
