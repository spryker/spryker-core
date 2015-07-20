<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemInterface;

interface CartClientInterface
{

    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @return CartInterface
     */
    public function clearCart();

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param CartItemInterface $cartItemTransfer
     *
     * @return CartInterface
     */
    public function addItem(CartItemInterface $cartItemTransfer);

    /**
     * @param CartItemInterface $cartItemTransfer
     *
     * @return CartInterface
     */
    public function removeItem(CartItemInterface $cartItemTransfer);

    /**
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function changeItemQuantity(CartItemInterface $cartItemTransfer, $quantity = 1);

    /**
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(CartItemInterface $cartItemTransfer, $quantity = 1);

    /**
     * @param CartItemInterface $cartItemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(CartItemInterface $cartItemTransfer, $quantity = 1);

    /**
     * @return CartInterface
     */
    public function recalculate();

    /**
     * @param string $coupon
     *
     * @return CartInterface
     */
    public function addCoupon($coupon);

    /**
     * @param string $coupon
     *
     * @return CartInterface
     */
    public function removeCoupon($coupon);

    /**
     * @return CartInterface
     */
    public function clearCoupons();

}
