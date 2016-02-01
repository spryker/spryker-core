<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart;

use Generated\Shared\Transfer\ItemTransfer;

interface CartClientInterface
{

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function getCart();

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCart();

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItem(ItemTransfer $itemTransfer);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate();

    /**
     * @param string $coupon
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCoupon($coupon);

    /**
     * @param string $coupon
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCoupon($coupon);

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCoupons();

}
