<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service\Zed;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerEngine\Shared\Transfer\TransferInterface;

interface CartStubInterface
{

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function addItem(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function removeItem(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function increaseItemQuantity(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function decreaseItemQuantity(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function addCoupon(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function removeCoupon(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return CartTransfer
     */
    public function clearCoupons(ChangeTransfer $changeTransfer);

    /**
     * @param CartTransfer|TransferInterface $cartTransfer
     *
     * @return CartTransfer
     */
    public function recalculate(CartTransfer $cartTransfer);

}
