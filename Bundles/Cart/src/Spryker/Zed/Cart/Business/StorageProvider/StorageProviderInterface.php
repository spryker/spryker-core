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
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItems(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCode(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCouponCode(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodes(CartTransfer $cart);

}
