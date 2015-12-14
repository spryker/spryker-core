<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface CartClientInterface
{

    /**
     * @return CartTransfer
     */
    public function getCart();

    /**
     * @return CartTransfer
     */
    public function clearCart();

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return CartTransfer
     */
    public function addItem(ItemTransfer $itemTransfer);

    /**
     * @param ItemTransfer $itemTransfer
     *
     * @return CartTransfer
     */
    public function removeItem(ItemTransfer $itemTransfer);

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return CartTransfer
     */
    public function changeItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return CartTransfer
     */
    public function decreaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @param ItemTransfer $itemTransfer
     * @param int $quantity
     *
     * @return CartTransfer
     */
    public function increaseItemQuantity(ItemTransfer $itemTransfer, $quantity = 1);

    /**
     * @return CartTransfer
     */
    public function recalculate();

    /**
     * @param string $coupon
     *
     * @return CartTransfer
     */
    public function addCoupon($coupon);

    /**
     * @param string $coupon
     *
     * @return CartTransfer
     */
    public function removeCoupon($coupon);

    /**
     * @return CartTransfer
     */
    public function clearCoupons();

}
