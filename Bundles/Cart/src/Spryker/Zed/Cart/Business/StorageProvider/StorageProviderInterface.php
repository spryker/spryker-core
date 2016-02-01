<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;

interface StorageProviderInterface
{

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCode(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param CartTransfer $cart
     * @param ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCouponCode(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodes(CartTransfer $cart);

}
