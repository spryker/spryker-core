<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Shared\Cart\Messages\Messages;
use Generated\Shared\Transfer\CartTransfer;

class DecreaseOperator extends AbstractOperator
{

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
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
