<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Shared\Cart\Messages\Messages;
use Generated\Shared\Transfer\CartTransfer;

class CouponCodeClearOperator extends AbstractOperator
{

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    protected function changeCart(CartTransfer $cart, ChangeTransfer $change)
    {
        $cart = $this->storageProvider->clearCouponCodes($cart);

        return $this->getGroupedCartItems($cart);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::COUPON_CODE_CLEAR_SUCCESS;
    }

}
