<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart;

use Generated\Shared\Transfer\ItemTransfer;

interface CartClientInterface
{

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function getCart();

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCart();

    /**
     * @api
     *
     * @return int
     */
    public function getItemCount();

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItem(ItemTransfer $itemTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate();

    /**
     * @api
     *
     * @param string $coupon
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCoupon($coupon);

    /**
     * @api
     *
     * @param string $coupon
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCoupon($coupon);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCoupons();

}
