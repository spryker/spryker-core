<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ItemInterface;

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
     * @param ItemInterface $itemTransfer
     *
     * @return CartInterface
     */
    public function addItem(ItemInterface $itemTransfer);

    /**
     * @param ItemInterface $itemTransfer
     *
     * @return CartInterface
     */
    public function removeItem(ItemInterface $itemTransfer);

    /**
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function changeItemQuantity(ItemInterface $itemTransfer, $quantity = 1);

    /**
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity(ItemInterface $itemTransfer, $quantity = 1);

    /**
     * @param ItemInterface $itemTransfer
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity(ItemInterface $itemTransfer, $quantity = 1);

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
