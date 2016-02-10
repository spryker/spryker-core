<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;

interface CartFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addToCart(ChangeTransfer $cartChange);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseQuantity(ChangeTransfer $cartChange);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeFromCart(ChangeTransfer $cartChange);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseQuantity(ChangeTransfer $cartChange);

    /**
     * @todo call calculator client from cart client.
     *
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate(CartTransfer $cart);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCode(ChangeTransfer $cartChange);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCouponCode(ChangeTransfer $cartChange);

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodes(ChangeTransfer $cartChange);

}
