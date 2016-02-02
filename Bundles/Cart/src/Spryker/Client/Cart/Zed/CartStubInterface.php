<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Shared\Transfer\TransferInterface;

interface CartStubInterface
{

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItem(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItem(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantity(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantity(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCoupon(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCoupon(ChangeTransfer $changeTransfer);

    /**
     * @param ChangeTransfer|TransferInterface $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCoupons(ChangeTransfer $changeTransfer);

    /**
     * @param CartTransfer|TransferInterface $cartTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate(CartTransfer $cartTransfer);

}
