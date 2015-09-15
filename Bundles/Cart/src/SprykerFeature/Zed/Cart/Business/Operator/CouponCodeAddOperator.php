<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Shared\Cart\Messages\Messages;
use Generated\Shared\Cart\CartInterface;

class CouponCodeAddOperator extends AbstractOperator
{

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    protected function changeCart(CartInterface $cart, ChangeInterface $change)
    {
        $this->storageProvider->addCouponCode($cart, $change);

        return $this->getGroupedCartItems($cart);
    }

    /**
     * @return string
     */
    protected function createSuccessMessage()
    {
        return Messages::COUPON_CODE_ADD_SUCCESS;
    }

}
