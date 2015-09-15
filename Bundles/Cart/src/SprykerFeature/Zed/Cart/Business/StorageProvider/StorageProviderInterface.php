<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;

interface StorageProviderInterface
{

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function addItems(CartInterface $cart, ChangeInterface $change);

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function removeItems(CartInterface $cart, ChangeInterface $change);

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function increaseItems(CartInterface $cart, ChangeInterface $change);

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function decreaseItems(CartInterface $cart, ChangeInterface $change);

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function addCouponCode(CartInterface $cart, ChangeInterface $change);

    /**
     * @param CartInterface $cart
     * @param ChangeInterface $change
     *
     * @return CartInterface
     */
    public function removeCouponCode(CartInterface $cart, ChangeInterface $change);

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    public function clearCouponCodes(CartInterface $cart);


}
