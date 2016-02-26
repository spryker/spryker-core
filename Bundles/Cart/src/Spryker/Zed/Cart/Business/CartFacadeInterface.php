<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;

interface CartFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addToCart(ChangeTransfer $cartChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseQuantity(ChangeTransfer $cartChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeFromCart(ChangeTransfer $cartChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseQuantity(ChangeTransfer $cartChange);

    /**
     * @api
     *
     * @todo call calculator client from cart client.
     *
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate(CartTransfer $cart);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCode(ChangeTransfer $cartChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCouponCode(ChangeTransfer $cartChange);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodes(ChangeTransfer $cartChange);

}
